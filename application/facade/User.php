<?php


namespace app\facade;

use think\Facade;
/**
 * Description of User
 *
 * @author zzr
 */
class User extends Facade{
    protected static function getFacadeClass() {
        return 'app\validate\User';
    }
}
