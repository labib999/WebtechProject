<?php
require_once __DIR__ . '/../config/db.php';;


function countPendingOrganiserApprovals() {
    $conn = getDB();
    $sql = "SELECT COUNT(*) as total FROM organiser_profiles WHERE status = 'pending'";
    $result = $conn->query($sql);
    return $result->fetch_assoc()['total'];
}


function countPendingVenueManagerApprovals() {
    $conn = getDB();
    $sql = "SELECT COUNT(*) as total FROM users WHERE role = 'venue_manager' AND is_active = 0";
    $result = $conn->query($sql);
    return $result->fetch_assoc()['total'];
}


function countUpcomingEvents() {
    $conn = getDB();
    $sql = "SELECT COUNT(*) as total FROM events 
            WHERE status = 'published' AND event_datetime > NOW()";
    $result = $conn->query($sql);
    return $result->fetch_assoc()['total'];
}


function countTicketsSoldToday() {
    $conn = getDB();
    $sql = "SELECT COUNT(*) as total FROM bookings 
            WHERE status = 'active' AND DATE(created_at) = CURDATE()";
    $result = $conn->query($sql);
    return $result->fetch_assoc()['total'];
}


function getTotalRevenueThisMonth() {
    $conn = getDB();
    $sql = "SELECT SUM(total_price) as total FROM bookings 
            WHERE status = 'active' 
            AND MONTH(created_at) = MONTH(NOW()) 
            AND YEAR(created_at) = YEAR(NOW())";
    $result = $conn->query($sql);
    $total = $result->fetch_assoc()['total'];
    return $total ? $total : 0;
}


function getPendingOrganisers() {
    $conn = getDB();
    $sql = "SELECT u.id, u.name, u.email, u.phone, u.created_at,
                   op.org_name, op.org_description, op.org_logo_path, op.id as profile_id
            FROM users u
            JOIN organiser_profiles op ON u.id = op.user_id
            WHERE op.status = 'pending'
            ORDER BY u.created_at DESC";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}


function getApprovedOrganisers() {
    $conn = getDB();
    $sql = "SELECT u.id, u.name, u.email, u.is_active, u.created_at,
                   op.org_name, op.status, op.id as profile_id
            FROM users u
            JOIN organiser_profiles op ON u.id = op.user_id
            WHERE op.status = 'approved' OR op.status = 'suspended'
            ORDER BY u.created_at DESC";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}


function approveOrganiser($profile_id) {
    $conn = getDB();
    $stmt = $conn->prepare("UPDATE organiser_profiles SET status = 'approved' WHERE id = ?");
    $stmt->bind_param("i", $profile_id);
    return $stmt->execute();
}


function rejectOrganiser($profile_id) {
    $conn = getDB();
    $stmt = $conn->prepare("UPDATE organiser_profiles SET status = 'rejected' WHERE id = ?");
    $stmt->bind_param("i", $profile_id);
    return $stmt->execute();
}

function suspendOrganiser($profile_id) {
    $conn = getDB();
    $stmt = $conn->prepare("UPDATE organiser_profiles SET status = 'suspended' WHERE id = ?");
    $stmt->bind_param("i", $profile_id);
    return $stmt->execute();
}


function reactivateOrganiser($profile_id) {
    $conn = getDB();
    $stmt = $conn->prepare("UPDATE organiser_profiles SET status = 'approved' WHERE id = ?");
    $stmt->bind_param("i", $profile_id);
    return $stmt->execute();
}


function getPendingVenueManagers() {
    $conn = getDB();
    $sql = "SELECT id, name, email, phone, created_at 
            FROM users 
            WHERE role = 'venue_manager' AND is_active = 0
            ORDER BY created_at DESC";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}


function approveVenueManager($id) {
    $conn = getDB();
    $stmt = $conn->prepare("UPDATE users SET is_active = 1 WHERE id = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}


function rejectVenueManager($id) {
    $conn = getDB();
    $stmt = $conn->prepare("UPDATE users SET is_active = 0 WHERE id = ? AND role = 'venue_manager'");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}


function getAllCategories() {
    $conn = getDB();
    $sql = "SELECT c.id, c.name, c.description, c.icon,
                   COUNT(e.id) as event_count
            FROM categories c
            LEFT JOIN events e ON c.id = e.category_id
            GROUP BY c.id
            ORDER BY c.name ASC";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}


function addCategory($name, $description, $icon) {
    $conn = getDB();
    $stmt = $conn->prepare("INSERT INTO categories (name, description, icon) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $description, $icon);
    return $stmt->execute();
}


function renameCategory($id, $name, $description, $icon) {
    $conn = getDB();
    $stmt = $conn->prepare("UPDATE categories SET name = ?, description = ?, icon = ? WHERE id = ?");
    $stmt->bind_param("sssi", $name, $description, $icon, $id);
    return $stmt->execute();
}


function deleteCategory($id) {
    $conn = getDB();
    
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM events WHERE category_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $count = $stmt->get_result()->fetch_assoc()['total'];
    if ($count > 0) {
        return false; 
    }
    $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}


function getAllEvents($status = '', $category_id = '') {
    $conn = getDB();
    $sql = "SELECT e.id, e.title, e.event_datetime, e.status, e.is_featured,
                   u.name as organiser_name,
                   c.name as category_name, c.icon as category_icon,
                   COUNT(b.id) as tickets_sold
            FROM events e
            JOIN users u ON e.organiser_id = u.id
            JOIN categories c ON e.category_id = c.id
            LEFT JOIN bookings b ON e.id = b.event_id AND b.status = 'active'
            WHERE 1=1";
    if ($status) $sql .= " AND e.status = '$status'";
    if ($category_id) $sql .= " AND e.category_id = '$category_id'";
    $sql .= " GROUP BY e.id ORDER BY e.event_datetime DESC";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}


function cancelEvent($id) {
    $conn = getDB();
    $stmt = $conn->prepare("UPDATE events SET status = 'cancelled' WHERE id = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}


function toggleFeatured($id, $is_featured) {
    $conn = getDB();
    $stmt = $conn->prepare("UPDATE events SET is_featured = ? WHERE id = ?");
    $stmt->bind_param("ii", $is_featured, $id);
    return $stmt->execute();
}


function countFeaturedEvents() {
    $conn = getDB();
    $sql = "SELECT COUNT(*) as total FROM events WHERE is_featured = 1";
    $result = $conn->query($sql);
    return $result->fetch_assoc()['total'];
}


function getOpenComplaints() {
    $conn = getDB();
    $sql = "SELECT c.id, c.description, c.created_at,
                   u1.name as submitter_name,
                   u2.name as against_name
            FROM complaints c
            JOIN users u1 ON c.submitter_id = u1.id
            JOIN users u2 ON c.against_id = u2.id
            WHERE c.status = 'open'
            ORDER BY c.created_at DESC";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}


function getResolvedComplaints() {
    $conn = getDB();
    $sql = "SELECT c.id, c.description, c.admin_note, c.created_at,
                   u1.name as submitter_name,
                   u2.name as against_name
            FROM complaints c
            JOIN users u1 ON c.submitter_id = u1.id
            JOIN users u2 ON c.against_id = u2.id
            WHERE c.status = 'resolved'
            ORDER BY c.created_at DESC";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}


function resolveComplaint($id, $note) {
    $conn = getDB();
    $stmt = $conn->prepare("UPDATE complaints SET status = 'resolved', admin_note = ? WHERE id = ?");
    $stmt->bind_param("si", $note, $id);
    return $stmt->execute();
}


function getAllAnnouncements() {
    $conn = getDB();
    $sql = "SELECT id, title, body, sent_at FROM admin_announcements ORDER BY sent_at DESC";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}


function postAnnouncement($title, $body) {
    $conn = getDB();
    $stmt = $conn->prepare("INSERT INTO admin_announcements (title, body, sent_at) VALUES (?, ?, NOW())");
    $stmt->bind_param("ss", $title, $body);
    return $stmt->execute();
}


function getCommissionRate() {
    $conn = getDB();
    $sql = "SELECT setting_value FROM platform_settings WHERE setting_key = 'default_commission_pct'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row ? $row['setting_value'] : 10;
}


function updateCommissionRate($rate) {
    $conn = getDB();
    $stmt = $conn->prepare("UPDATE platform_settings SET setting_value = ? WHERE setting_key = 'default_commission_pct'");
    $stmt->bind_param("d", $rate);
    return $stmt->execute();
}


function getFinancialSummary() {
    $conn = getDB();
    $sql = "SELECT 
                SUM(total_price) as gross_sales,
                COUNT(*) as total_transactions
            FROM bookings 
            WHERE status = 'active'
            AND MONTH(created_at) = MONTH(NOW())
            AND YEAR(created_at) = YEAR(NOW())";
    $result = $conn->query($sql);
    return $result->fetch_assoc();
}


function getTopEventsByRevenue() {
    $conn = getDB();
    $sql = "SELECT e.title, u.name as organiser_name,
                   SUM(b.total_price) as revenue,
                   COUNT(b.id) as tickets_sold
            FROM bookings b
            JOIN events e ON b.event_id = e.id
            JOIN users u ON e.organiser_id = u.id
            WHERE b.status = 'active'
            GROUP BY e.id
            ORDER BY revenue DESC
            LIMIT 5";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}


function getTopOrganisersByRevenue() {
    $conn = getDB();
    $sql = "SELECT u.name as organiser_name,
                   COUNT(DISTINCT e.id) as event_count,
                   SUM(b.total_price) as revenue
            FROM bookings b
            JOIN events e ON b.event_id = e.id
            JOIN users u ON e.organiser_id = u.id
            WHERE b.status = 'active'
            GROUP BY u.id
            ORDER BY revenue DESC
            LIMIT 5";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}


function getRevenueByCategory() {
    $conn = getDB();
    $sql = "SELECT c.name, c.icon,
                   COUNT(DISTINCT e.id) as event_count,
                   COUNT(b.id) as tickets_sold,
                   SUM(b.total_price) as revenue
            FROM bookings b
            JOIN events e ON b.event_id = e.id
            JOIN categories c ON e.category_id = c.id
            WHERE b.status = 'active'
            GROUP BY c.id
            ORDER BY revenue DESC";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}