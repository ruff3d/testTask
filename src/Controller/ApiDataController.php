<?php
declare(strict_types=1);

namespace TestTask\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class ApiDataController extends Controller
{
    /**
     * @Route("/api/data")
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function index()
    {
        $response = json_decode(file_get_contents(__DIR__ . '/apiResource/data.json'));
//        var_dump( $response);
        return $this->json($response);
    }
}