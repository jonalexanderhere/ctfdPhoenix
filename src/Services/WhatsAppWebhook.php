<?php

namespace CTFd\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

/**
 * WhatsApp Webhook Service
 * Mengirim notifikasi First Blood ke WhatsApp
 */
class WhatsAppWebhook
{
    private $webhookUrl;
    private $enabled;
    private $client;
    
    public function __construct()
    {
        $this->webhookUrl = $_ENV['WHATSAPP_WEBHOOK_URL'] ?? '';
        $this->enabled = !empty($this->webhookUrl) && ($_ENV['WHATSAPP_ENABLED'] ?? 'false') === 'true';
        $this->client = new Client([
            'timeout' => 10,
            'verify' => true
        ]);
    }
    
    /**
     * Check if webhook is enabled
     */
    public function isEnabled()
    {
        return $this->enabled;
    }
    
    /**
     * Send First Blood notification
     * 
     * @param string $userName Nama user
     * @param string $affiliation Sekolah/Affiliation user
     * @param string $challengeName Nama challenge
     * @param string $category Kategori challenge
     * @param bool $isPractice Apakah challenge practice
     * @return bool Success status
     */
    public function sendFirstBlood($userName, $affiliation, $challengeName, $category, $isPractice = false)
    {
        if (!$this->isEnabled()) {
            return false;
        }
        
        // Format message
        $practiceText = $isPractice ? '[Practice]' : '';
        $message = "Congrats {$userName} - {$affiliation}!\n\n";
        $message .= "🔥 FIRST BLOOD! 🔥\n";
        $message .= "on {$challengeName} {$practiceText} [{$category}]";
        
        return $this->sendMessage($message);
    }
    
    /**
     * Send generic message
     */
    public function sendMessage($message)
    {
        if (!$this->isEnabled()) {
            return false;
        }
        
        try {
            $response = $this->client->post($this->webhookUrl, [
                'json' => [
                    'message' => $message
                ],
                'headers' => [
                    'Content-Type' => 'application/json'
                ]
            ]);
            
            $statusCode = $response->getStatusCode();
            return $statusCode >= 200 && $statusCode < 300;
            
        } catch (RequestException $e) {
            error_log("WhatsApp webhook error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Send solve notification (non-first blood)
     */
    public function sendSolve($userName, $challengeName, $category)
    {
        if (!$this->isEnabled()) {
            return false;
        }
        
        $message = "✅ {$userName} solved {$challengeName} [{$category}]";
        return $this->sendMessage($message);
    }
}

