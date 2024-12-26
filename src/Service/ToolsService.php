<?php

namespace App\Service;

use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ToolsService
{
    private JWTEncoderInterface $JWTEncoder;

    private ParameterBagInterface $params;

    public function __construct(JWTEncoderInterface $JWTEncoder, ParameterBagInterface $params)
    {
        $this->JWTEncoder = $JWTEncoder;
        $this->params = $params;
    }

    /**
     * @throws JWTDecodeFailureException
     */
    public function decodeJwtToken(string $token): array
    {
        return $this->JWTEncoder->decode($token);
    }

    /**
     * @throws JWTDecodeFailureException
     */
    public function getUserIdByToken(string $token): ?string
    {
        //dd($this->decodeJwtToken($token));

        $decoded = $this->decodeJwtToken($token);

        return $decoded['id'] ?? null;
    }

    public function getImageUrl(string $image): ?string
    {
        if ($image) {
            return $this->params->get('app.base_url') . '/uploads/images/' . $image;
        }

        return null;
    }

    public function getVideoUrl(string $video): ?string
    {
        if ($video) {
            return $this->params->get('app.base_url') . '/uploads/videos/' . $video;
        }

        return null;
    }
}