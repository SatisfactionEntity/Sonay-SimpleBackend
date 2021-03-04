<link rel="stylesheet" href="/Simple/Assets/css/Sonay/style.css?<?= time() ?>">
    <link rel="stylesheet" href="/Simple/Assets/css/profile/pure.css">
        <div class="row">
			<div class="col-sm-6">
				<div id="shadow-box" style="max-height:100%">
					<div class="title-box png20" style="background-color:#00796B;background-image:url(/simple/Assets/img/vault.png);background-repeat: no-repeat;background-position: right;background-size: cover;height: 80px;">
						<div class="title2"><font color="white">Kraak de Kluis</font></div>
						<div class="desc2"><font color="white">Doe hier een poging om de kluis te kraken!</font></div>
					</div>
					<div class="png20 stataantal">
            <div class="box">
                <div class="inner">
                    <div class="error"></div>
                    <div class="success"></div>
                    <center>
                        <select id="kluis" class="num1">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="0">0</option>
                        </select>
                        <select id="kluis" class="num2">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="0">0</option>
                        </select>
                        <select id="kluis" class="num3">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="0">0</option>
                        </select>
                        <select id="kluis" class="num4">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="0">0</option>
                        </select>
                        <br><br>
                        <button type="submit" class="btn btn-success">Kraak de kluis</button>
                        <br/>
                        <br/>
                        <strong id="trys" style="color:#d32f2f">
                            <?php
                            $Keys = (int)CMS::$MySql->single('SELECT vaultkeys FROM cms_presents WHERE userid = :userid', array('userid' => Users::$Session->ID));
                            if ($Keys == 0) {
                                echo CMS::$Lang['vaultnokeys'];
                            } else {
                                echo str_ireplace("%amount%", $Keys, CMS::$Lang['vaulttimes']);
                            }
                            ?>                        </strong>
                    </center>
                </div>
            </div>
					</div>
				</div>
				<div id="shadow-box" style="max-height:100%">
					<div class="title-box png20" style="background-color:#00796B;background-image:url(/Simple/Assets/img/painting.png);background-repeat: no-repeat;background-position: right;background-size: cover;height: 80px;">
						<div class="title2"><font color="white">Prijzen</font></div>
						<div class="desc2"><font color="white">Deze prijzen te kraken of zijn al gekraakt</font></div>
					</div>
					<div class="png20 stataantal">
						<table class="stataantal">
		                    <thead>
    		                <tr>
    		                    <th>Prijs</th>
    		                    <th width="20%">Info</th>
    		                    <th>Gekraakt</th>
    		                    <th>Kraker</th>
    		                </tr>
    		                </thead>
    		                <tbody>
							<?php
                    if ($Prices = CMS::$MySql->query('SELECT cms_vault.id,type,price,info,username FROM cms_vault LEFT JOIN users ON users.id = cracker_id')) {
                        foreach ($Prices as $Row) {
                            if ($Row['type'] == 'badge') {
                                $Row['url'] = CMS::$Config['cms']['badgemap'] . '/' . $Row['price'] . '.gif';
                            } else if ($Row['type'] == 'furni') {
                                $Row['url'] = CMS::$Config['cms']['furniiconmap'] . '/' . explode(';', $Row['price'])[0].'_icon.png';
                            } else {
                                $Row['url'] = '/Simple/Assets/img/diamonds.png';
                            }
                            echo '<tr id="'.$Row['id'].'"><td><img src="'.$Row['url'].'"></td><td>'.$Row['info'].'</td><td id="cracked">'.($Row['username'] ? CMS::$Lang['yes'] : CMS::$Lang['no']).'</td><td id="username">'.($Row['username'] ? $Row['username'] : CMS::$Lang['none']).'</td>';
                        }
                    }
                    ?>
    		                   		                </tbody>
    		            </table>
					</div>
				</div>
			</div>
			<div class="col-6">
				<div id="shadow-box" style="max-height:100%">
					<div class="title-box png20" style="background-color:#00796B;background-image:url(/Simple/Assets/img/ducks.png);background-repeat: no-repeat;background-position: right;background-size: cover;height: 80px;">
						<div class="title2"><font color="white">Kraak de Kluis Uitleg</font></div>
						<div class="desc2"><font color="white">Hoe werkt kraak de kluis precies?</font></div>
					</div>
					<div class="png20 stataantal">
                <img src="/Simple/Assets/img/kluis.png" align="right" style="margin-top: 10px;margin-right: 20px;">
                <?= str_ireplace('%hotelname%', CMS::$Config['cms']['hotelname'], CMS::$Lang['vaultinfo']) ?>					</div>
				</div>
			</div>
        </div>
    </div>
    <script type="text/javascript" src="/Simple/Assets/js/global/script.js"></script>
    <script type="text/javascript" src="/Simple/Assets/js/loaders/vault.js"></script>