<div class="row">
    <div style="display:none" id="editplayer" class="col-md-12">
        <section class="panel">
            <header class="panel-heading">
                <div>Edit a player</div>
            </header>
            <div class="panel-body">
                <div style="display:none" class="alert alert-block alert-success"></div>
                <div style="display:none" class="alert alert-block alert-danger"></div>
                <div class="form-group">
                    <label class="col-sm-2 col-sm-2 control-label">Username</label>
                    <div class="col-sm-10">
                        <input id="editusername" class="form-control">
                    </div>
                </div><br><br>
                <div class="form-group">
                    <label class="col-sm-2 col-sm-2 control-label">Email</label>
                    <div class="col-sm-10">
                        <input id="editemail" type="email" class="form-control">
                    </div>
                </div><br><br>
                <div class="form-group">
                    <label class="col-sm-2 col-sm-2 control-label">Password</label>
                    <div class="col-sm-10">
                        <input id="editpassword" type="password" class="form-control">
                    </div>
                </div><br><br>
                <div class="form-group">
                    <label class="col-sm-2 col-sm-2 control-label">Credits</label>
                    <div class="col-sm-10">
                        <input id="editcredits" class="form-control">
                    </div>
                </div><br><br>
                <div class="form-group">
                    <label class="col-sm-2 col-sm-2 control-label">Duckets</label>
                    <div class="col-sm-10">
                        <input id="editduckets" class="form-control">
                    </div>
                </div><br><br>
                <div class="form-group">
                    <label class="col-sm-2 col-sm-2 control-label">Diamonds</label>
                    <div class="col-sm-10">
                        <input id="editdiamonds" class="form-control">
                    </div>
                </div><br><br>
                <div class="form-group">
                    <label class="col-sm-2 col-sm-2 control-label">Crowns</label>
                    <div class="col-sm-10">
                        <input id="editcrowns" class="form-control">
                    </div>
                </div><br><br>
                <div class="form-group">
                    <label class="col-sm-2 col-sm-2 control-label">Rank</label>
                    <div class="col-sm-10">
                        <select id="editrank" class="form-control" style="width:100%;font-size:14px">
                        <?php
                        $Ranks = CMS::$MySql->query('SELECT id,rank_name FROM permissions WHERE id < :rank ORDER BY id DESC', array('rank' => Users::$Session->Data['rank']));
                        foreach($Ranks as $Rank) {
                            echo '<option data-id="'.$Rank['id'].'">'.$Rank['rank_name'].'</option>';
                        }
                        ?>
                        </select>
                        <br><br>
                    </div>
                </div>
                <div id="buttons" style="float:right;margin-top:10px">
                    <button style="width:130px;margin-right:14px" id="update" data-id="0" class="btn btn-success">Update player</button>
                    <?php if (Users::$Session->HasPermission(CMS::$Config['manage']['ban']))
                        echo '<button style="width:130px;margin-right:14px" id="unban" data-id="0" class="btn btn-warning">Unban</button>';
                    ?>
                    <button style="width:130px;margin-right:14px" id="cancelupdate" class="btn btn-danger">Close</button>
                </div>
            </div>
        </section>
    </div>
    <div id="searchresults" style="display:none" class="col-md-12">
        <section class="panel">
            <header class="panel-heading">
                Search result<br>
                <div class="panel-body">
                    <table class="table table-striped table-bordered table-condensed">
                        <tr><td style="width: 5%;"><b>ID</b></td><td><b>Username</b></td><td><b>Email</b></td><td><b>Current IP</b></td><td><b>Last online</b></td><td><b><center>Edit</center></b></td></td><td><b><center>Login</center></b></td></td></tr>
                    </table>
                    <div id="buttons" style="float:right;margin-top:10px">
                        <button style="width:130px;margin-right:14px" id="close" class="btn btn-danger">Close</button>
                    </div>
                </div>
            </header>
    </div>
    <div id="searchfield" class="col-md-12">
        <section class="panel">
            <header class="panel-heading">
                <div>Search for users</div>
            </header>
            <div class="panel-body">
                <div style="display:none" class="alert alert-block alert-danger"></div>
                <div class="form-group">
                    <label class="col-sm-2 col-sm-2 control-label">Username</label>
                    <div class="col-sm-10">
                        <input id="username" class="form-control">
                    </div>
                </div><br><br>
                <div class="form-group">
                    <label class="col-sm-2 col-sm-2 control-label">IP address</label>
                    <div class="col-sm-10">
                        <input id="ip" class="form-control">
                    </div>
                </div><br><br>
							  <div class="form-group">
                    <label class="col-sm-2 col-sm-2 control-label">Exact search?</label>
                    <div id="strict" class="col-sm-10">
                        <label class="radio-inline"><input type="radio" name="strict" value="1">Yes</label>
                        <label class="radio-inline"><input type="radio" name="strict" value="0" checked="checked" />No</label>
                    </div>
                </div><br><br>
                <div id="buttons" style="float:right;margin-top:10px">
                    <button style="width:130px;margin-right:14px" id="search" class="btn btn-success">Search users</button>
                </div>
            </div>
        </section>
    </div>
    <div class="col-md-12">
        <section class="panel">
            <header class="panel-heading">
                Last registered users<br>
                <div class="panel-body">
                    <table class="table table-striped table-bordered table-condensed">
                        <tr><td style="width: 5%;"><b>ID</b></td><td><b>Username</b></td><td><b>Email</b></td><td><b>Current IP</b></td><td><b>Last online</b></td><td><b><center>Edit</center></b></td></td><td><b><center>Login</center></b></td></td></tr>
                        <?php
                            $Players = CMS::$MySql->query('SELECT id,username,mail,last_online,ip_current FROM users WHERE rank < :rank ORDER BY id DESC LIMIT 25', array('rank' => Users::$Session->Data['rank']));
                            foreach($Players as $Row)
                            {
                                echo '<tr data-id="'.$Row['id'].'">
											<td>'.$Row['id'].'</td>
											<td>'.$Row["username"].'</td>
											<td>'.$Row["mail"].'</td>
											<td>'.$Row["ip_current"].'</td>
											<td>'. date('d-m-Y H:i:s', $Row['last_online']).'</td>
											<td><center><div class="edit" data-id="'.$Row['id'].'"><i style="padding-top:5px;color:green" class="fa fa-edit"></i></center></div></td>
											<td><center><div class="login" data-id="'.$Row['id'].'"><i style="padding-top:5px;color:red" class="fa fa-sign-in"></i></center></div></td>
											</tr>';
                            }
                        ?>
                    </table>
                </div>
            </header>
    </div>
</div>
<script src="/Simple/Assets/manage/js/loaders/users.js"></script>