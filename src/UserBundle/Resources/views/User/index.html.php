<?php
use UserBundle\Entity\Role;

/**
 * @var $app            Symfony\Bundle\FrameworkBundle\Templating\GlobalVariables
 * @var $view           Symfony\Bundle\FrameworkBundle\Templating\TimedPhpEngine
 * @var $slotsHelper    ToolboxBundle\Helper\SlotsHelper
 * @var $routerHelper   Symfony\Bundle\FrameworkBundle\Templating\Helper\RouterHelper
 *
 * @var $currentUser    UserBundle\Entity\User
 * @var $users          array
 */


$slotsHelper = $view['slots'];
$routerHelper = $view['router'];

$view->extend('::loggedIn.html.php');
?>

<?php $slotsHelper->start('title'); ?>User<?php $slotsHelper->stop(); ?>

<?php $slotsHelper->append('header-css') ?>
    <style>
        .table .btn-holder {
            width: 70px;
        }

        .edit-link {
            position:absolute;
            left:0;
            top:0;
            width:100%;
            height:100%;
            display:block;
            padding:8px;
        }

        .table td {
            position:relative;
        }

        .table .btn-holder .btn-xs {
            position: absolute;
            left: 25px;
            top: 7px;
        }

        .table > thead > tr > th,
        .table > tbody > tr > td {
            padding: 8px 0;
        }

        .table tr:hover .btn-holder .btn-xs {
            left: 24px;
            top: 3px;
            font-size: 16px;
        }

        .table tr .btn-holder a {
            position: absolute;
            display: block;
            width: 100%;
            height: 100%;
            left: 0;
            top: 0;
        }

        @media(max-width: 767px) {
            .table,
            a#new-user {
                font-size: 20px;
            }


            .table .btn-holder .btn-xs {
                left: 25px;
                top: 10px;
            }

            .table tr:hover .btn-holder .btn-xs {
                left: 24px;
                top: 7px;
            }
        }
    </style>
<?php $slotsHelper->stop(); ?>

<?php $slotsHelper->start('content') ?>
    <div class="row">
        <div class="col-sm-offset-4 col-sm-4 col-xs-offset-2 col-xs-8 no-padding">
            <table id="user-holder" class="table table-striped table-hover text-center">
                <thead>
                <tr>
                    <th class="text-center">User</th>
                    <th class="text-center">Rolle</th>
                    <th class="text-center">Option</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($users as $user): ?>
                    <tr data-user-id="<?php echo $user['id']; ?>">
                        <td>&nbsp;
                            <a class="edit-link" href="<?php echo $routerHelper->generate('user_edit', ['user' => $user['id']]); ?>">
                                <?php echo $user['name']; ?>
                            </a>
                        </td>
                        <td>
                            <?php switch($user['role']):
                                case Role::ROLE_APPLICANT: ?>
                                    Lehrer
                                    <?php break; ?>
                                <?php case Role::ROLE_ADMIN: ?>
                                    Admin
                                    <?php break; ?>
                                <?php case Role::ROLE_SUPER_ADMIN: ?>
                                    Superadmin
                                    <?php break; ?>
                                <?php endswitch; ?>
                        </td>
                        <td class="btn-holder">
                            <a href="#">
                                <span class="btn btn-danger btn-xs">
                                    <span class="glyphicon glyphicon-remove remove"></span>
                                </span>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if(count($users) <= 0): ?>
                    <tr class="btn-holder">
                        <td colspan="3"><b>Bisher keine Einträge</b></td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>
        <div class="col-sm-offset-4 col-xs-offset-2 col-xs-8 no-padding">
            <!-- Button trigger modal -->
            <a id="new-user" class="btn btn-sm btn-primary" href="<?php echo $routerHelper->generate('user_create'); ?>">
                User anlegen
            </a>
        </div>
    </div>
<?php $slotsHelper->stop(); ?>