<?php

declare(strict_types=1);

namespace Santi\PromocionesHome\ViewModel;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

class Promociones implements ArgumentInterface
{
    public function __construct(
        private StoreManagerInterface $storeManager,
        private ScopeConfigInterface $scopeConfig
    ) {}

    /**
     * Devuelve los datos configurados del banner de promociones.
     *
     * Si no está habilitado o no hay imagen/vídeo configurado,
     * devuelve array vacío para que el PHTML pueda usar su fallback.
     */
    public function getBanner(): array
    {
        $base = rtrim($this->storeManager->getStore()->getBaseUrl(), '/');
        $mediaBase = rtrim(
            $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA),
            '/'
        );

        $p = 'santi_promocioneshome/banner/';

        $enabled = (string) $this->get($p . 'enabled', '0');
        if ($enabled !== '1') {
            return [];
        }

        $image = (string) $this->get($p . 'image', '');
        $alt = (string) $this->get($p . 'image_alt', '');
        $video = (string) $this->get($p . 'video', '');
        $poster = (string) $this->get($p . 'poster', '');
        $title = (string) $this->get($p . 'title', '');
        $subtitle = (string) $this->get($p . 'subtitle', '');
        $btnTxt = (string) $this->get($p . 'button_text', '');
        $btnUrl = (string) $this->get($p . 'button_url', '');
        $color = (string) $this->get($p . 'color_primary', '#8cc4b2');

        $imageUrl = $image
            ? $mediaBase . '/santi_promocioneshome/' . ltrim($image, '/')
            : '';

        $videoUrl = $video
            ? $mediaBase . '/santi_promocioneshome/' . ltrim($video, '/')
            : '';

        $posterUrl = $poster
            ? $mediaBase . '/santi_promocioneshome/' . ltrim($poster, '/')
            : ($imageUrl ?: '');

        if ($btnUrl && !preg_match('#^https?://#i', $btnUrl) && $btnUrl[0] !== '/') {
            $btnUrl = $base . '/' . ltrim($btnUrl, '/');
        }

        // Si no hay ni imagen ni vídeo, devolver vacío para que el PHTML use fallback.
        if (!$imageUrl && !$videoUrl) {
            return [];
        }

        return [
            'is_video'      => (bool) $videoUrl,
            'image_url'     => $imageUrl,
            'image_alt'     => $alt,
            'video_url'     => $videoUrl,
            'poster_url'    => $posterUrl,
            'title'         => $title,
            'subtitle'      => $subtitle,
            'button_text'   => $btnTxt,
            'button_url'    => $btnUrl,
            'color_primary' => $color,
        ];
    }

    private function get(string $path, $default = null)
    {
        $v = $this->scopeConfig->getValue($path, ScopeInterface::SCOPE_STORE);
        return $v !== null ? $v : $default;
    }
}
