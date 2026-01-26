<?php

namespace App\Helpers;

class BreadcrumbHelper
{
    /**
     * Generate breadcrumbs berdasarkan route
     */
    public static function generate()
    {
        $breadcrumbs = [];
        $routeName = request()->route()->getName();
        $segments = explode('.', $routeName);

        // Dashboard tidak perlu breadcrumbs (sudah ada Home)
        if ($routeName === 'home') {
            return $breadcrumbs;
        }

        // Master data breadcrumbs
        if (isset($segments[0]) && $segments[0] === 'master') {
            $breadcrumbs[] = [
                'label' => 'Master',
                'url' => '#'
            ];

            // Specific master pages
            if (isset($segments[1])) {
                $masterPages = [
                    'unor' => ['label' => 'Unit Organisasi', 'url' => route('master.unor.index')],
                    'unit-kerja' => ['label' => 'Unit Kerja', 'url' => route('master.unit-kerja.index')],
                    'waktu-konsumsi' => ['label' => 'Waktu Konsumsi', 'url' => route('master.waktu-konsumsi.index')],
                    'mak' => ['label' => 'MAK (Akun)', 'url' => route('master.mak.index')],
                    'ppk' => ['label' => 'PPK', 'url' => route('master.ppk.index')],
                    'bendahara' => ['label' => 'Bendahara', 'url' => route('master.bendahara.index')],
                    'sbm-konsumsi' => ['label' => 'SBM Konsumsi', 'url' => route('master.sbm-konsumsi.index')],
                    'sbm-honorarium' => ['label' => 'SBM Honorarium', 'url' => route('master.sbm-honorarium')],
                ];

                if (isset($masterPages[$segments[1]])) {
                    $breadcrumbs[] = $masterPages[$segments[1]];

                    // Add action breadcrumb (create, edit, etc)
                    if (isset($segments[2])) {
                        $actions = [
                            'create' => 'Tambah Baru',
                            'edit' => 'Edit',
                            'show' => 'Detail',
                        ];
                        if (isset($actions[$segments[2]])) {
                            $breadcrumbs[] = [
                                'label' => $actions[$segments[2]],
                                'url' => '#'
                            ];
                        }
                    }
                }
            }
        }

        // Kegiatan breadcrumbs
        elseif (isset($segments[0]) && $segments[0] === 'kegiatan') {
            $breadcrumbs[] = [
                'label' => 'Kegiatan',
                'url' => route('kegiatan.index')
            ];

            if (isset($segments[1])) {
                $actions = [
                    'create' => 'Tambah Kegiatan',
                    'edit' => 'Edit Kegiatan',
                    'show' => 'Detail Kegiatan',
                    'pilih-detail' => 'Detail Belanja Bahan',
                ];
                if (isset($actions[$segments[1]])) {
                    $breadcrumbs[] = [
                        'label' => $actions[$segments[1]],
                        'url' => '#'
                    ];
                }
            }
        }

        // Konsumsi breadcrumbs
        elseif (isset($segments[0]) && $segments[0] === 'konsumsi') {
            $kegiatanId = request()->route()->parameter('kegiatan_id');

            if ($kegiatanId) {
                $breadcrumbs[] = [
                    'label' => 'Kegiatan',
                    'url' => route('kegiatan.index')
                ];
                $breadcrumbs[] = [
                    'label' => 'Detail Kegiatan',
                    'url' => route('kegiatan.pilih-detail', $kegiatanId)
                ];
            }

            $breadcrumbs[] = [
                'label' => 'Konsumsi',
                'url' => '#'
            ];

            if (isset($segments[1])) {
                $actions = [
                    'create' => 'Tambah Konsumsi',
                    'validasi' => 'Validasi Konsumsi',
                ];
                if (isset($actions[$segments[1]])) {
                    $breadcrumbs[] = [
                        'label' => $actions[$segments[1]],
                        'url' => '#'
                    ];
                }
            }
        }

        // Barang breadcrumbs
        elseif (isset($segments[0]) && $segments[0] === 'barang') {
            $kegiatanId = request()->route()->parameter('kegiatan_id');

            if ($kegiatanId) {
                $breadcrumbs[] = [
                    'label' => 'Kegiatan',
                    'url' => route('kegiatan.index')
                ];
                $breadcrumbs[] = [
                    'label' => 'Detail Kegiatan',
                    'url' => route('kegiatan.pilih-detail', $kegiatanId)
                ];
            }

            $breadcrumbs[] = [
                'label' => 'Barang',
                'url' => '#'
            ];

            if (isset($segments[1]) && $segments[1] === 'create') {
                $breadcrumbs[] = [
                    'label' => 'Tambah Barang',
                    'url' => '#'
                ];
            }
        }

        // Honorarium breadcrumbs
        elseif (isset($segments[0]) && $segments[0] === 'honorarium') {
            $kegiatanId = request()->route()->parameter('kegiatan_id');

            if ($kegiatanId) {
                $breadcrumbs[] = [
                    'label' => 'Kegiatan',
                    'url' => route('kegiatan.index')
                ];
                $breadcrumbs[] = [
                    'label' => 'Detail Kegiatan',
                    'url' => route('kegiatan.pilih-detail', $kegiatanId)
                ];
            }

            $breadcrumbs[] = [
                'label' => 'Honorarium',
                'url' => '#'
            ];

            if (isset($segments[1]) && $segments[1] === 'create') {
                $breadcrumbs[] = [
                    'label' => 'Tambah Honorarium',
                    'url' => '#'
                ];
            }
        }

        // Narasumber breadcrumbs
        elseif (isset($segments[0]) && $segments[0] === 'narasumber') {
            $kegiatanId = request()->route()->parameter('kegiatan_id');

            if ($kegiatanId) {
                $breadcrumbs[] = [
                    'label' => 'Kegiatan',
                    'url' => route('kegiatan.index')
                ];
                $breadcrumbs[] = [
                    'label' => 'Detail Kegiatan',
                    'url' => route('kegiatan.pilih-detail', $kegiatanId)
                ];
            }

            $breadcrumbs[] = [
                'label' => 'Narasumber',
                'url' => '#'
            ];

            if (isset($segments[1])) {
                $actions = [
                    'create' => 'Tambah Narasumber',
                    'daftar-hadir' => 'Daftar Hadir',
                    'daftar-honorarium' => 'Daftar Honorarium',
                ];
                if (isset($actions[$segments[1]])) {
                    $breadcrumbs[] = [
                        'label' => $actions[$segments[1]],
                        'url' => '#'
                    ];
                }
            }
        }

        // Kwitansi breadcrumbs
        elseif (isset($segments[0]) && $segments[0] === 'kwitansi') {
            $kegiatanId = request()->route()->parameter('kegiatan_id');

            if ($kegiatanId) {
                $breadcrumbs[] = [
                    'label' => 'Kegiatan',
                    'url' => route('kegiatan.index')
                ];
                $breadcrumbs[] = [
                    'label' => 'Detail Kegiatan',
                    'url' => route('kegiatan.pilih-detail', $kegiatanId)
                ];
            }

            $breadcrumbs[] = [
                'label' => 'Kwitansi',
                'url' => '#'
            ];

            if (isset($segments[1])) {
                $actions = [
                    'preview' => 'Preview Kwitansi',
                    'daftar-hadir' => 'Daftar Hadir',
                ];
                if (isset($actions[$segments[1]])) {
                    $breadcrumbs[] = [
                        'label' => $actions[$segments[1]],
                        'url' => '#'
                    ];
                }
            }
        }

        // Users breadcrumbs
        elseif (isset($segments[0]) && $segments[0] === 'users') {
            $breadcrumbs[] = [
                'label' => 'User Management',
                'url' => route('users.index')
            ];

            if (isset($segments[1])) {
                $actions = [
                    'create' => 'Tambah User',
                    'edit' => 'Edit User',
                ];
                if (isset($actions[$segments[1]])) {
                    $breadcrumbs[] = [
                        'label' => $actions[$segments[1]],
                        'url' => '#'
                    ];
                }
            }
        }

        return $breadcrumbs;
    }
}
