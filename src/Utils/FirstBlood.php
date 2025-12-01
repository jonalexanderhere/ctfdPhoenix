<?php

namespace CTFd\Utils;

use CTFd\Database;
use CTFd\Services\WhatsAppWebhook;

/**
 * First Blood Detection and Notification
 */
class FirstBlood
{
    /**
     * Check if this is the first solve for a challenge
     * 
     * @param int $challengeId Challenge ID
     * @return bool True if first solve
     */
    public static function isFirstSolve($challengeId)
    {
        $db = Database::getInstance();
        
        // Count total solves for this challenge
        $sql = "SELECT COUNT(*) as count FROM solves WHERE challenge_id = :challenge_id";
        $result = $db->fetchOne($sql, ['challenge_id' => $challengeId]);
        
        return ($result['count'] ?? 0) == 1;
    }
    
    /**
     * Send First Blood notification
     * 
     * @param int $userId User ID
     * @param int $challengeId Challenge ID
     * @return bool Success status
     */
    public static function sendNotification($userId, $challengeId)
    {
        if (!self::isFirstSolve($challengeId)) {
            return false;
        }
        
        $db = Database::getInstance();
        $webhook = new WhatsAppWebhook();
        
        // Get user info
        $userSql = "SELECT name, affiliation FROM users WHERE id = :user_id";
        $user = $db->fetchOne($userSql, ['user_id' => $userId]);
        
        if (!$user) {
            return false;
        }
        
        // Get challenge info
        $challengeSql = "SELECT name, category, state FROM challenges WHERE id = :challenge_id";
        $challenge = $db->fetchOne($challengeSql, ['challenge_id' => $challengeId]);
        
        if (!$challenge) {
            return false;
        }
        
        $userName = $user['name'] ?? 'Unknown';
        $affiliation = $user['affiliation'] ?? 'Unknown';
        $challengeName = $challenge['name'] ?? 'Unknown Challenge';
        $category = $challenge['category'] ?? 'Unknown';
        $isPractice = ($challenge['state'] ?? 'visible') === 'practice';
        
        // Send webhook
        return $webhook->sendFirstBlood(
            $userName,
            $affiliation,
            $challengeName,
            $category,
            $isPractice
        );
    }
}

