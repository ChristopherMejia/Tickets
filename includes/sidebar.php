<?php $menu = $permission->showMenu($user_id); ?>
<?php $submenu = $permission->showSubMenu($user_id); ?>


<div id="sidebar-menu" class="main_menu_side hidden-print main_menu"><!-- sidebar menu -->
    <div class="menu_section">
        <ul class="nav side-menu">

            <?php foreach ($menu as $m) { ?>


                <li>
                    <a href="<?php echo $m['prefix'] ?>">
                        <i class="fa <?php echo $m['icon'] ?>"></i> <?php echo $m['name'] ?>
                    </a>

                </li>

                <?php
            } ?>


            <!--            <li class=" dropdown">-->
            <!--                <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown"-->
            <!--                   aria-haspopup="true" aria-expanded="false">-->
            <!--                    <i class="fa fa-folder"></i>-->
            <!--                    <span>Utilerias</span>-->
            <!--                </a>-->
            <!--                <div class="dropdown-menu" aria-labelledby="pagesDropdown">-->
            <!--                    <h6 class="dropdown-header">Varias:</h6>-->
            <!--                    <a class="dropdown-item" href="cfdi-uuid-cancel-xml">CFDI UUID Cancelar</a>-->
            <!--                </div>-->
            <!--            </li>-->

        </ul>
    </div>
</div><!-- /sidebar menu -->
</div>
</div>

<div class="top_nav"><!-- top navigation -->
    <div class="nav_menu">
        <nav>
            <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>

            </div>


            <ul class="nav navbar-nav navbar-right">
                <li class="">
                    <a href="javascript:" class="user-profile dropdown-toggle" data-toggle="dropdown"
                       aria-expanded="false">
                        <img src="images/profiles/<?php echo $profile_pic; ?>" alt=""><?php echo $name; ?>
                        <span class="fa fa-angle-down"></span>
                    </a>

                    <ul class="dropdown-menu dropdown-usermenu pull-right">
                        <li><a href="profile"><i class="fa fa-user"></i> Mi cuenta</a></li>
                        <li><a href="configuration"><i class="fa fa-cog"></i> Configuración</a></li>
                        <li><a href="action/logout.php"><i class="fa fa-sign-out pull-right"></i> Cerrar Sesión</a></li>
                    </ul>


                </li>
                <li class="">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><span
                                class="label label-pill label-danger count-notifi" style="border-radius:10px;"></span>
                        <span
                                class="glyphicon glyphicon-bell" style="font-size:18px;"></span></a>
                    <ul class="dropdown-menu" id="noti">
                    </ul>
                </li>
                <li class="">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <span class="label label-pill label-warning count-notifi-msg"
                              style="border-radius:10px;">

                        </span>
                        <span class="glyphicon glyphicon-comment" style="font-size:18px;"></span></a>
                    <ul class="dropdown-menu" id="noti-msg"></ul>
                </li>
                <!-- Countdown Session -->
                <li style="display: none">
                    <a href="#">
                        <p id='SecondsUntilExpire'></p>
                    </a>
                </li>


                <li class="action-hidden">
                    <div id="users-presence-div" style="display: <?php echo $div_usersPrecense ?>;"></div>
                </li>
            </ul>
        </nav>
    </div>
</div><!-- /top navigation -->