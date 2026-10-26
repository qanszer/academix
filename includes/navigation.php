<?php

const NAVIGATION_LINKS_BY_ROLE = [
    ROLE_STUDENT => [
        [
            'page' => 'dashboard',
            'label' => 'Home',
            'icon' => 'home',
            'group' => 'Overview',
        ],
        [
            'page' => 'new-request',
            'label' => 'New request',
            'icon' => 'edit',
            'group' => 'Documents',
        ],
        [
            'page' => 'my-requests',
            'label' => 'My requests',
            'icon' => 'list',
            'group' => 'Documents',
        ],
    ],
    ROLE_STAFF => [
        [
            'page' => 'request-queue',
            'label' => 'Request queue',
            'icon' => 'list',
            'group' => 'Processing',
        ],
    ],
    ROLE_ADMIN => [
        [
            'page' => 'university-integration',
            'label' => 'Integration',
            'icon' => 'link',
            'group' => 'Configuration',
        ],
        [
            'page' => 'employees',
            'label' => 'Employees',
            'icon' => 'users',
            'group' => 'Configuration',
        ],
        [
            'page' => 'appearance',
            'label' => 'Appearance',
            'icon' => 'palette',
            'group' => 'Configuration',
        ],
        [
            'page' => 'settings',
            'label' => 'Settings',
            'icon' => 'sliders',
            'group' => 'Configuration',
        ],
    ],
];

function renderNavigationLinks(string $role, string $currentPage): string
{
    $linksHtml = '';
    $currentGroup = '';

    foreach (NAVIGATION_LINKS_BY_ROLE[$role] as $link) {
        if ($link['group'] !== $currentGroup) {
            $currentGroup = $link['group'];
            $linksHtml .=
                "<div class=\"sidebar-group-label sidebar-label\">" .
                "$currentGroup</div>";
        }

        $activeClass = $link['page'] === $currentPage ? ' active' : '';
        $icon = renderIcon($link['icon']);

        $linksHtml .= <<<HTML
            <a class="nav-link$activeClass" href="{$link['page']}.php">
                $icon<span class="sidebar-label">{$link['label']}</span>
            </a>
            HTML;
    }

    return $linksHtml;
}
