<?php

namespace Olx\controllers\Account;

use Olx\controllers\CBaseController;

class CAdminController extends CBaseController {

    public function getAdminAc ( $req , $res )   {

        return $this->view->render($res, 'account/admin.twig');
    }
}