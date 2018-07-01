<?php

class StatsController extends \Phalcon\Mvc\Controller
{

    public function getAdmins() {
        // Returna usuarios con roles SUPERADMIN_ROLE y ADMIN_ROLE para sección soporte
        return array('response' => true, 'superadm' => Users::findByRole('SUPERADMIN_ROLE'), 'adm' => Users::findByRole('ADMIN_ROLE'));
    }

}

