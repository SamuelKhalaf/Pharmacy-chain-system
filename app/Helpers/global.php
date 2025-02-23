<?php

define('PAGINATE_COUNT',5);

//helper function to set active if the route is equal to the anchor href
function setActive($routeName, $paramName = null, $paramValue = null)
{
    $isActive = request()->routeIs($routeName);

    if ($paramName && $paramValue) {
        return $isActive && request()->route($paramName) == $paramValue ? 'active' : '';
    }

    return $isActive ? 'active' : '';
}

//  Determines whether a sidebar menu item should be "open"  or "closed".
function setMenuOpen($routePrefix)
{
    return request()->is($routePrefix . '*') ? 'menu-open' : '';
}
