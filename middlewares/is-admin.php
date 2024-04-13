<?php

if (!User::isInRole(1)) {
    Response::badRequest("access_denied")->send();
}
