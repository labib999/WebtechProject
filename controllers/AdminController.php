<?php
session_start();
require_once '../config/db.php';
require_once '../models/AdminModel.php';
require_once '../models/UserModel.php';
require_once '../models/EventModel.php';


function checkAdminAuth() {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
        header('Location: ../views/shared/login.php');
        exit;
    }
}


function showDashboard() {
    checkAdminAuth();


    $totalUsers        = countTotalUsers();
    $upcomingEvents    = countUpcomingEvents();
    $ticketsToday      = countTicketsSoldToday();
    $revenueThisMonth  = getTotalRevenueThisMonth();
    $pendingOrganisers = countPendingOrganiserApprovals();
    $pendingVenues     = countPendingVenueManagerApprovals();
    $pendingTotal      = $pendingOrganisers + $pendingVenues;


    require_once '../views/admin/dashboard.php';
}


// APPROVALS

function showApprovals() {
    checkAdminAuth();

    
    $pendingOrganisers  = getPendingOrganisers();
    $approvedOrganisers = getApprovedOrganisers();

    
    $pendingVenues = getPendingVenueManagers();

    
    require_once '../views/admin/approvals.php';
}

// Approve an organiser
function handleApproveOrganiser() {
    checkAdminAuth();
    $profile_id = (int)$_POST['profile_id'];
    approveOrganiser($profile_id);
    $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Organiser approved successfully!'];
    header('Location: ../views/admin/approvals.php');
    exit;
}

// Reject an organiser
function handleRejectOrganiser() {
    checkAdminAuth();
    $profile_id = (int)$_POST['profile_id'];
    rejectOrganiser($profile_id);
    $_SESSION['flash'] = ['type' => 'warning', 'msg' => 'Organiser rejected.'];
    header('Location: ../views/admin/approvals.php');
    exit;
}

// Suspend an organiser
function handleSuspendOrganiser() {
    checkAdminAuth();
    $profile_id = (int)$_POST['profile_id'];
    suspendOrganiser($profile_id);
    $_SESSION['flash'] = ['type' => 'warning', 'msg' => 'Organiser suspended.'];
    header('Location: ../views/admin/approvals.php');
    exit;
}

// Reactivate an organiser
function handleReactivateOrganiser() {
    checkAdminAuth();
    $profile_id = (int)$_POST['profile_id'];
    reactivateOrganiser($profile_id);
    $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Organiser reactivated successfully!'];
    header('Location: ../views/admin/approvals.php');
    exit;
}

// Approve a venue manager
function handleApproveVenue() {
    checkAdminAuth();
    $id = (int)$_POST['user_id'];
    approveVenueManager($id);
    $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Venue manager approved successfully!'];
    header('Location: ../views/admin/approvals.php');
    exit;
}

// Reject a venue manager
function handleRejectVenue() {
    checkAdminAuth();
    $id = (int)$_POST['user_id'];
    rejectVenueManager($id);
    $_SESSION['flash'] = ['type' => 'warning', 'msg' => 'Venue manager rejected.'];
    header('Location: ../views/admin/approvals.php');
    exit;
}


// CATEGORIES

function showCategories() {
    checkAdminAuth();

    
    $categories = getAllCategories();

    
    require_once '../views/admin/categories.php';
}

// Add a new category
function handleAddCategory() {
    checkAdminAuth();

    $name        = trim($_POST['name']);
    $description = trim($_POST['description']);
    $icon        = trim($_POST['icon']);

    // Validation
    if (empty($name)) {
        $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Category name is required.'];
        header('Location: ../views/admin/categories.php');
        exit;
    }

    if (empty($icon)) {
        $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Please enter an emoji icon.'];
        header('Location: ../views/admin/categories.php');
        exit;
    }

    addCategory($name, $description, $icon);
    $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Category added successfully!'];
    header('Location: ../views/admin/categories.php');
    exit;
}

// Rename/edit a category
function handleEditCategory() {
    checkAdminAuth();

    $id          = (int)$_POST['id'];
    $name        = trim($_POST['name']);
    $description = trim($_POST['description']);
    $icon        = trim($_POST['icon']);

    // Validation
    if (empty($name)) {
        $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Category name is required.'];
        header('Location: ../views/admin/categories.php');
        exit;
    }

    renameCategory($id, $name, $description, $icon);
    $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Category updated successfully!'];
    header('Location: ../views/admin/categories.php');
    exit;
}

// Delete a category
function handleDeleteCategory() {
    checkAdminAuth();

    $id     = (int)$_POST['id'];
    $result = deleteCategory($id);

    if (!$result) {
        $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Cannot delete — events exist in this category.'];
    } else {
        $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Category deleted successfully!'];
    }

    header('Location: ../views/admin/categories.php');
    exit;
}


// EVENTS

function showEvents() {
    checkAdminAuth();

  
    $status      = isset($_GET['status']) ? $_GET['status'] : '';
    $category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : '';

    
    $events     = getAllEvents($status, $category_id);
    $categories = getAllCategories();

    
    require_once '../views/admin/events.php';
}

// Cancel an event
function handleCancelEvent() {
    checkAdminAuth();

    $id = (int)$_POST['event_id'];
    cancelEvent($id);
    $_SESSION['flash'] = ['type' => 'warning', 'msg' => 'Event has been cancelled.'];
    header('Location: ../views/admin/events.php');
    exit;
}

// Toggle featured status
function handleToggleFeatured() {
    checkAdminAuth();

    $id          = (int)$_POST['event_id'];
    $is_featured = (int)$_POST['is_featured'];

    // If trying to feature an event, check the 5 limit first
    if ($is_featured === 1) {
        $currentFeatured = countFeaturedEvents();
        if ($currentFeatured >= 5) {
            $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Maximum 5 featured events allowed. Unfeature one first.'];
            header('Location: ../views/admin/events.php');
            exit;
        }
    }

    toggleFeatured($id, $is_featured);
    $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Featured status updated!'];
    header('Location: ../views/admin/events.php');
    exit;
}


// COMPLAINTS

function showComplaints() {
    checkAdminAuth();

    $openComplaints     = getOpenComplaints();
    $resolvedComplaints = getResolvedComplaints();

    require_once '../views/admin/complaints.php';
}

// Resolve a complaint
function handleResolveComplaint() {
    checkAdminAuth();

    $id   = (int)$_POST['complaint_id'];
    $note = trim($_POST['resolution_note']);

    
    if (empty($note)) {
        $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Please add a resolution note before closing the complaint.'];
        header('Location: ../views/admin/complaints.php');
        exit;
    }

    resolveComplaint($id, $note);
    $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Complaint resolved successfully!'];
    header('Location: ../views/admin/complaints.php');
    exit;
}


// ANNOUNCEMENTS

function showAnnouncements() {
    checkAdminAuth();

    $announcements = getAllAnnouncements();

    require_once '../views/admin/announcements.php';
}

// Post a new announcement
function handlePostAnnouncement() {
    checkAdminAuth();

    $title = trim($_POST['title']);
    $body  = trim($_POST['body']);


    if (empty($title)) {
        $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Announcement title is required.'];
        header('Location: ../views/admin/announcements.php');
        exit;
    }

    if (empty($body)) {
        $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Announcement message is required.'];
        header('Location: ../views/admin/announcements.php');
        exit;
    }

    postAnnouncement($title, $body);
    $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Announcement posted successfully!'];
    header('Location: ../views/admin/announcements.php');
    exit;
}


// USERS

function showUsers() {
    checkAdminAuth();

    $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';

    if (!empty($keyword)) {
        $users = searchUsers($keyword);
    } else {
        $users = getAllUsers();
    }

    require_once '../views/admin/users.php';
}

// Suspend a user
function handleSuspendUser() {
    checkAdminAuth();

    $id = (int)$_POST['user_id'];
    suspendUser($id);
    $_SESSION['flash'] = ['type' => 'warning', 'msg' => 'User suspended successfully.'];
    header('Location: ../views/admin/users.php');
    exit;
}

// Reactivate a user
function handleReactivateUser() {
    checkAdminAuth();

    $id = (int)$_POST['user_id'];
    reactivateUser($id);
    $_SESSION['flash'] = ['type' => 'success', 'msg' => 'User reactivated successfully!'];
    header('Location: ../views/admin/users.php');
    exit;
}


// FINANCIAL REPORT

function showFinancialReport() {
    checkAdminAuth();

    $summary      = getFinancialSummary();
    $topEvents    = getTopEventsByRevenue();
    $topOrganisers = getTopOrganisersByRevenue();
    $byCategory   = getRevenueByCategory();
    $commission   = getCommissionRate();

    require_once '../views/admin/financial_report.php';
}

// Update commission rate
function handleUpdateCommission() {
    checkAdminAuth();

    $rate = (float)$_POST['commission_rate'];

    if ($rate < 0 || $rate > 100) {
        $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Commission rate must be between 0 and 100.'];
        header('Location: ../views/admin/financial_report.php');
        exit;
    }

    updateCommissionRate($rate);
    $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Commission rate updated successfully!'];
    header('Location: ../views/admin/financial_report.php');
    exit;
}


// ACTION ROUTER


$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case 'dashboard':
        showDashboard();
        break;

    case 'approvals':
        showApprovals();
        break;

    case 'approve_organiser':
        handleApproveOrganiser();
        break;

    case 'reject_organiser':
        handleRejectOrganiser();
        break;

    case 'suspend_organiser':
        handleSuspendOrganiser();
        break;

    case 'reactivate_organiser':
        handleReactivateOrganiser();
        break;

    case 'approve_venue':
        handleApproveVenue();
        break;

    case 'reject_venue':
        handleRejectVenue();
        break;

    case 'categories':
        showCategories();
        break;

    case 'add_category':
        handleAddCategory();
        break;

    case 'edit_category':
        handleEditCategory();
        break;

    case 'delete_category':
        handleDeleteCategory();
        break;

    case 'events':
        showEvents();
        break;

    case 'cancel_event':
        handleCancelEvent();
        break;

    case 'toggle_featured':
        handleToggleFeatured();
        break;

    case 'complaints':
        showComplaints();
        break;

    case 'resolve_complaint':
        handleResolveComplaint();
        break;

    case 'announcements':
        showAnnouncements();
        break;

    case 'post_announcement':
        handlePostAnnouncement();
        break;

    case 'users':
        showUsers();
        break;

    case 'suspend_user':
        handleSuspendUser();
        break;

    case 'reactivate_user':
        handleReactivateUser();
        break;

    case 'financial_report':
        showFinancialReport();
        break;

    case 'update_commission':
        handleUpdateCommission();
        break;

    default:
        showDashboard();
        break;
}