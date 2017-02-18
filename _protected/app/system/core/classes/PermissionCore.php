<?php
/**
 * @author         Pierre-Henry Soria <ph7software@gmail.com>
 * @copyright      (c) 2012-2017, Pierre-Henry Soria. All Rights Reserved.
 * @license        GNU General Public License; See PH7.LICENSE.txt and PH7.COPYRIGHT.txt in the root directory.
 * @package        PH7 / App / System / Core / Class
 */
namespace PH7;

defined('PH7') or exit('Restricted access');

use
PH7\Framework\Layout\Html\Design,
PH7\Framework\Url\Header,
PH7\Framework\Mvc\Router\Uri;

abstract class PermissionCore extends Framework\Core\Core
{
    protected $group;

    public function __construct()
    {
        parent::__construct();

        $this->group = UserCoreModel::checkGroup();
    }

    /**
     * Checks whether the user membership is still valid or not.
     *
     * @return boolean Returns TRUE if the membership is still valid (or user not logged), FALSE otherwise.
     */
    public function checkMembership()
    {
        if (UserCore::auth()) {
            return (new UserCoreModel)->checkMembershipExpiration(
                $this->session->get('member_id'),
                $this->dateTime->get()->dateTime('Y-m-d H:i:s')
            );
        } else {
            return true;
        }
    }

    public function signUpRedirect()
    {
        Header::redirect(
            Uri::get('user','signup','step1'),
            $this->signUpMsg(),
            Design::ERROR_TYPE
        );
    }

    public function signInRedirect()
    {
        Header::redirect(
            Uri::get('user','main','login'),
            $this->signInMsg(),
            Design::ERROR_TYPE
        );
    }

    public function alreadyConnectedRedirect()
    {
        Header::redirect(
            Uri::get('user','account','index'),
            $this->alreadyConnectedMsg(),
            Design::ERROR_TYPE
        );
    }

    /**
     * Redirect the user to the payment page when it is on a page that requires another membership.
     *
     * @return void
     */
    public function paymentRedirect()
    {
        Header::redirect(
            Uri::get('payment','main','index'),
            $this->upgradeMembershipMsg(),
            Design::WARNING_TYPE
        );
    }

    public function signInMsg()
    {
        return t('Please sign in first');
    }

    public function adminSignInMsg()
    {
        return t('Please go to the admin panel and log in as administrator.');
    }

    public function alreadyConnectedMsg()
    {
        return t('Oops! You are already connected.');
    }

    public function signUpMsg()
    {
        return t('Please register or login to continue.');
    }

    public function upgradeMembershipMsg()
    {
        return t('Please upgrade your membership!');
    }
}
