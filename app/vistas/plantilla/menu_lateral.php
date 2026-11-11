<?php
$accionActual = $_GET['accion'] ?? 'dashboard';
?>
<!-- Barra lateral / Menú de navegación (Figma Pantalla #3) -->
<aside class="sidebar">
    <a href="index.php?accion=dashboard" class="sidebar-brand">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="2" y="6" width="20" height="12" rx="2"></rect>
            <path d="M6 12h4"></path>
            <path d="M8 10v4"></path>
            <line x1="15" y1="11" x2="15.01" y2="11"></line>
            <line x1="18" y1="13" x2="18.01" y2="13"></line>
        </svg>
        <span>LEVEL UP <span class="brand-games">GAMES</span></span>
    </a>

    <ul class="sidebar-nav">
        <li>
            <a href="index.php?accion=dashboard" class="sidebar-link <?= ($accionActual === 'dashboard') ? 'active' : '' ?>">
                <div class="sidebar-link-content">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                    <span>Panel Principal</span>
                </div>
            </a>
        </li>

        <li>
            <a href="index.php?accion=pedidos" class="sidebar-link <?= (str_starts_with($accionActual, 'pedidos')) ? 'active' : '' ?>">
                <div class="sidebar-link-content">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                    <span>Pedidos</span>
                </div>
            </a>
        </li>

        <li>
            <a href="index.php?accion=productos" class="sidebar-link <?= (str_starts_with($accionActual, 'productos')) ? 'active' : '' ?>">
                <div class="sidebar-link-content">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                    <span>Productos</span>
                </div>
            </a>
        </li>

        <?php if (esAdministrador()): ?>
        <li>
            <a href="index.php?accion=categorias" class="sidebar-link <?= (str_starts_with($accionActual, 'categorias')) ? 'active' : '' ?>">
                <div class="sidebar-link-content">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
                    <span>Categorías</span>
                </div>
            </a>
        </li>
        <?php endif; ?>

        <li>
            <a href="index.php?accion=clientes" class="sidebar-link <?= (str_starts_with($accionActual, 'clientes')) ? 'active' : '' ?>">
                <div class="sidebar-link-content">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    <span>Clientes</span>
                </div>
            </a>
        </li>

        <?php if (esAdministrador()): ?>
        <li>
            <a href="index.php?accion=reportes" class="sidebar-link <?= (str_starts_with($accionActual, 'reportes')) ? 'active' : '' ?>">
                <div class="sidebar-link-content">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
                    <span>Reportes</span>
                </div>
            </a>
        </li>
        <?php endif; ?>

        <li style="margin-top: 10px;">
            <a href="index.php?accion=menu" target="_blank" class="sidebar-link" style="color: var(--color-lime);">
                <div class="sidebar-link-content">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                    <span>Ver Catálogo</span>
                </div>
            </a>
        </li>
    </ul>

    <!-- Tarjeta de usuario logueado con cerrar sesión -->
    <div class="sidebar-user">
        <div class="user-info">
            <span class="user-name"><?= escapar($_SESSION['nombre_usuario'] ?? 'Usuario') ?></span>
            <span class="user-role"><?= escapar($_SESSION['rol_nombre'] ?? 'Cajero') ?></span>
        </div>
        <a href="index.php?accion=logout" class="btn-logout" title="Cerrar sesión">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
        </a>
    </div>
</aside>

<div class="main-wrapper">
    <!-- Barra superior -->
    <header class="top-navbar">
        <div class="breadcrumb-trail">
            Gestión / <span><?= escapar($titulo ?? 'Panel') ?></span>
        </div>
        <div>
            <span class="badge badge-purple"><?= escapar($_SESSION['rol_nombre'] ?? 'Invitado') ?></span>
        </div>
    </header>

    <main class="content-area">
        <?php 
        $mensajeFlash = obtenerMensaje();
        if ($mensajeFlash): 
        ?>
            <div class="alert alert-<?= ($mensajeFlash['tipo'] === 'peligro') ? 'danger' : (($mensajeFlash['tipo'] === 'advertencia') ? 'warning' : 'success') ?>">
                <span><?= escapar($mensajeFlash['texto']) ?></span>
            </div>
        <?php endif; ?>
