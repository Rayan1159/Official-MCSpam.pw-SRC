<?php
/**
 * Created by PhpStorm.
 * User: bas
 * Date: 20-4-19
 * Time: 19:44
 */
?>
<div class="left-sidenav">

    <ul class="metismenu left-sidenav-menu" id="side-nav">

        <li class="menu-title">Main</li>

        <li>
            <a href="index"><i class="mdi mdi-monitor"></i><span>Dashboard</span></span></a>
        </li>

        <li class="menu-title">Attack panels</li>

        <li>
            <a href="hub"><i class="mdi mdi-power"></i><span>Minecraft HUB (Free)</span></i></span></a>
        </li>


        <li class="menu-title">Support</li>

        <li>
            <a href="ticket" ><i class="mdi mdi-ticket"></i><span>Ticket Center</span></i></span></a>
        </li>

        <li>
            <a href="https://discord.gg/HqCdweh" target="_blank"><i class="mdi mdi-discord"></i><span>Discord</span></i></span></a>
        </li>

        <li class="menu-title">Purchase</li>
        <li>
            <a id="pricing" onclick="pricing()"><i class="mdi mdi-currency-usd"></i><span>Pricing</span></i></span></a id>
        </li>
        <li>
            <a href="redeem"><i class="mdi mdi-check"></i><span>Redeem license code</span></i></span></a>
        </li>

        <li class="menu-title">MCSpam tools</li>

        <li>
            <a href="resolve"><i class="mdi mdi-toolbox"></i><span>Resolving tools</span></i></span></a>
        </li>

        <?php
            if ($user->hasPermission() === true) {
        ?>

        <li class="menu-title">Administration</li>

        <li>
            <a href="/admin/index"><i class="mdi mdi-hammer"></i><span>Administrator</span></i></span></a>
        </li>

        <?php } ?>

    </ul>
    <script>
        function pricing() {
            $.ajax('inc/Requests/priceRequest', {
                data: {
                    enabled: 1
                },
                method: "POST",
                success:function (getResp) {
                    if (getResp === "disabled") {
                        return toastr['error']("Purchasing is currently disabled. We're waiting on more servers to increase power.", "MCSpam");
                    }
                    if(getResp === "enabled") {
                        return window.location = "pricing.php";
                    }
                }
            });
        }
    </script>
</div>
