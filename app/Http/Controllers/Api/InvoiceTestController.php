<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class InvoiceTestController extends Controller
{
    /**
     * Test invoice API endpoints
     */
    public function testEndpoints(): JsonResponse
    {
        $baseUrl = url('/api');
        
        $endpoints = [
            'GET' => [
                'List Invoices' => "{$baseUrl}/invoices",
                'Get Invoice by Booking' => "{$baseUrl}/invoices/booking/{bookingId}",
                'Generate PDF' => "{$baseUrl}/invoices/booking/{bookingId}/pdf",
                'Download PDF' => "{$baseUrl}/invoices/booking/{bookingId}/download",
            ],
            'POST' => [
                'Create Invoice (Admin/Staff)' => "{$baseUrl}/invoices",
            ],
            'PUT' => [
                'Update Invoice (Admin/Staff)' => "{$baseUrl}/invoices/{invoiceId}",
            ]
        ];

        $sampleRequests = [
            'Create Invoice' => [
                'method' => 'POST',
                'url' => "{$baseUrl}/invoices",
                'headers' => [
                    'Authorization' => 'Bearer {your_token}',
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ],
                'body' => [
                    'booking_id' => 1,
                    'amount' => 2500000
                ]
            ],
            'Get Invoice' => [
                'method' => 'GET',
                'url' => "{$baseUrl}/invoices/booking/1",
                'headers' => [
                    'Authorization' => 'Bearer {your_token}',
                    'Accept' => 'application/json'
                ]
            ],
            'Generate PDF' => [
                'method' => 'GET',
                'url' => "{$baseUrl}/invoices/booking/1/pdf",
                'headers' => [
                    'Authorization' => 'Bearer {your_token}',
                    'Accept' => 'application/json'
                ]
            ],
            'Download PDF' => [
                'method' => 'GET',
                'url' => "{$baseUrl}/invoices/booking/1/download",
                'headers' => [
                    'Authorization' => 'Bearer {your_token}',
                    'Accept' => 'application/pdf'
                ]
            ]
        ];

        return response()->json([
            'success' => true,
            'message' => 'Invoice API Documentation',
            'data' => [
                'endpoints' => $endpoints,
                'sample_requests' => $sampleRequests,
                'authentication' => [
                    'type' => 'Bearer Token (Laravel Sanctum)',
                    'header' => 'Authorization: Bearer {token}',
                    'note' => 'Get token from /api/login endpoint'
                ],
                'permissions' => [
                    'Customer' => 'Can view and download their own invoices',
                    'Staff' => 'Can view all invoices, create and update invoices',
                    'Admin' => 'Can view all invoices, create and update invoices'
                ],
                'response_format' => [
                    'success' => true,
                    'message' => 'Success message',
                    'data' => 'Response data'
                ]
            ]
        ]);
    }
}
