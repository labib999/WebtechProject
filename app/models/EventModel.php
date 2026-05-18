<?php
class EventModel extends Model {

    public function getAllByOrganiser($orgId, $status = 'all', $search = '') {
        $sql    = "SELECT e.*, c.name as category_name,
                          COUNT(DISTINCT b.id) as bookings_count,
                          COALESCE(SUM(CASE WHEN b.status='active' THEN b.total_price ELSE 0 END),0) as revenue
                   FROM events e
                   LEFT JOIN categories c ON e.category_id = c.id
                   LEFT JOIN bookings b   ON b.event_id = e.id
                   WHERE e.organiser_id = ?";
        $params = [$orgId];
        $types  = "i";

        if ($status !== 'all') {
            $sql .= " AND e.status = ?"; $params[] = $status; $types .= "s";
        }
        if (!empty($search)) {
            $sql .= " AND e.title LIKE ?"; $params[] = '%'.$search.'%'; $types .= "s";
        }
        $sql .= " GROUP BY e.id ORDER BY e.created_at DESC";
        return $this->db->query($sql, $types, $params);
    }

    public function getById($id, $orgId) {
        $rows = $this->db->query(
            "SELECT e.*, c.name as category_name FROM events e
             LEFT JOIN categories c ON e.category_id=c.id
             WHERE e.id=? AND e.organiser_id=?",
            "ii", [$id, $orgId]
        );
        return $rows[0] ?? null;
    }

    public function getStatusCounts($orgId) {
        $rows = $this->db->query(
            "SELECT status, COUNT(*) as cnt FROM events
             WHERE organiser_id=? GROUP BY status",
            "i", [$orgId]
        );
        $counts = ['all'=>0,'published'=>0,'draft'=>0,'cancelled'=>0,'completed'=>0];
        foreach ($rows as $r) {
            $counts[$r['status']] = (int)$r['cnt'];
            $counts['all'] += (int)$r['cnt'];
        }
        return $counts;
    }

    public function create($orgId, $data, $bannerPath) {
        // Types: i(orgId) s(title) s(desc) i(catId) s(evDate) s(endDate)
        //        s(venue) s(addr) s(city) i(cap) s(banner) = 11 params
        $this->db->execute(
            "INSERT INTO events
               (organiser_id, title, description, category_id,
                event_datetime, end_datetime, venue_name_override,
                venue_address, venue_city, max_capacity,
                banner_image_path, status)
             VALUES (?,?,?,?,?,?,?,?,?,?,?,'draft')",
            "issississis",
            [
                $orgId,
                $data['title']              ?? '',
                $data['description']        ?? '',
                $data['category_id']        ?? null,
                $data['event_datetime']     ?? '',
                $data['end_datetime']       ?? '',
                $data['venue_name_override']?? '',
                $data['venue_address']      ?? '',
                $data['venue_city']         ?? '',
                $data['max_capacity']       ?? null,
                $bannerPath
            ]
        );
    }

    public function update($id, $orgId, $data, $bannerPath = null) {
        if ($bannerPath) {
            // Types: s(title) s(desc) i(cat) s(evDate) s(endDate)
            //        s(venue) s(addr) s(city) i(cap) s(banner) i(id) i(orgId) = 12 params
            $this->db->execute(
                "UPDATE events
                 SET title=?, description=?, category_id=?,
                     event_datetime=?, end_datetime=?,
                     venue_name_override=?, venue_address=?,
                     venue_city=?, max_capacity=?, banner_image_path=?
                 WHERE id=? AND organiser_id=?",
                "ssississsisii",
                [
                    $data['title']              ?? '',
                    $data['description']        ?? '',
                    $data['category_id']        ?? null,
                    $data['event_datetime']     ?? '',
                    $data['end_datetime']       ?? '',
                    $data['venue_name_override']?? '',
                    $data['venue_address']      ?? '',
                    $data['venue_city']         ?? '',
                    $data['max_capacity']       ?? null,
                    $bannerPath,
                    $id,
                    $orgId
                ]
            );
        } else {
            // Types: s(title) s(desc) i(cat) s(evDate) s(endDate)
            //        s(venue) s(addr) s(city) i(cap) i(id) i(orgId) = 11 params
            $this->db->execute(
                "UPDATE events
                 SET title=?, description=?, category_id=?,
                     event_datetime=?, end_datetime=?,
                     venue_name_override=?, venue_address=?,
                     venue_city=?, max_capacity=?
                 WHERE id=? AND organiser_id=?",
                "ssississsiii",
                [
                    $data['title']              ?? '',
                    $data['description']        ?? '',
                    $data['category_id']        ?? null,
                    $data['event_datetime']     ?? '',
                    $data['end_datetime']       ?? '',
                    $data['venue_name_override']?? '',
                    $data['venue_address']      ?? '',
                    $data['venue_city']         ?? '',
                    $data['max_capacity']       ?? null,
                    $id,
                    $orgId
                ]
            );
        }
    }

    public function publish($id, $orgId) {
        $this->db->execute(
            "UPDATE events SET status='published'
             WHERE id=? AND organiser_id=? AND status='draft'",
            "ii", [$id, $orgId]
        );
    }

    public function cancel($id, $orgId) {
        $this->db->execute(
            "UPDATE events SET status='cancelled'
             WHERE id=? AND organiser_id=?",
            "ii", [$id, $orgId]
        );
    }

    public function getCategories() {
        return $this->db->query(
            "SELECT id, name FROM categories ORDER BY name"
        );
    }

    public function getPublished($orgId) {
        return $this->db->query(
            "SELECT id, title, event_datetime FROM events
             WHERE organiser_id=? AND status='published'
             ORDER BY event_datetime DESC",
            "i", [$orgId]
        );
    }
}