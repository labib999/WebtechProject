<?php
class ProfileModel extends Model {

    public function getUser($userId) {
        $rows = $this->db->query(
            "SELECT * FROM users WHERE id=?",
            "i", [$userId]
        );
        return $rows[0] ?? [];
    }

    public function getProfile($userId) {
        $rows = $this->db->query(
            "SELECT * FROM organiser_profiles WHERE user_id=?",
            "i", [$userId]
        );
        return $rows[0] ?? [];
    }

    public function updateUser($userId, $name, $phone) {
        $this->db->execute(
            "UPDATE users SET name=?, phone=? WHERE id=?",
            "ssi", [$name, $phone, $userId]
        );
    }

    public function updateProfile($userId, $orgName, $orgDesc, $website) {
        $this->db->execute(
            "UPDATE organiser_profiles
             SET org_name=?, org_description=?, website=?
             WHERE user_id=?",
            "sssi", [$orgName, $orgDesc, $website, $userId]
        );
    }

    public function updateProfileWithLogo($userId, $orgName, $orgDesc, $website, $logoPath) {
        $this->db->execute(
            "UPDATE organiser_profiles
             SET org_name=?, org_description=?, website=?, org_logo_path=?
             WHERE user_id=?",
            "ssssi", [$orgName, $orgDesc, $website, $logoPath, $userId]
        );
    }

    public function updatePassword($userId, $hash) {
        $this->db->execute(
            "UPDATE users SET password_hash=? WHERE id=?",
            "si", [$hash, $userId]
        );
    }

    public function getLogo($userId) {
        $rows = $this->db->query(
            "SELECT org_logo_path FROM organiser_profiles WHERE user_id=?",
            "i", [$userId]
        );
        return $rows[0]['org_logo_path'] ?? null;
    }
}