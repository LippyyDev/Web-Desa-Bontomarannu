<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 *
 * Extend this class in any new controllers:
 * ```
 *     class Home extends BaseController
 * ```
 *
 * For security, be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Helpers yang otomatis dimuat di seluruh controller.
     *
     * @var array
     */
    protected $helpers = ['url', 'form', 'text'];

    /**
     * Session service instance.
     *
     * @var \CodeIgniter\Session\Session
     */
    protected $session;

    /**
     * Data user yang sedang login (jika ada).
     *
     * @var array|null
     */
    protected $currentUser;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->session   = service('session');
        $this->currentUser = $this->session->get('user');

        // Update last_seen_at secara throttled (maks 1x per 60 detik per user)
        // Tidak memberatkan performa: query ringan (UPDATE by PK), dibatasi frekuensinya via session
        if ($this->currentUser && isset($this->currentUser['id'])) {
            $lastPing = $this->session->get('last_seen_ping') ?? 0;
            if ((time() - $lastPing) >= 60) {
                $db = \Config\Database::connect();
                $db->query('UPDATE users SET last_seen_at = NOW() WHERE id = ?', [$this->currentUser['id']]);
                $this->session->set('last_seen_ping', time());
            }
        }
    }
}
