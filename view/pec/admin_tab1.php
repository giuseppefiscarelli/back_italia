<div class="it-list-wrapper">
        <div class="it-header-navbar-wrapper theme-light-desk">
            <div class="container">
                <div class="row">
                <div class="col-12">
                    <!--start nav-->
                    <nav class="navbar navbar-expand-lg has-megamenu">
                    <button class="custom-navbar-toggler" type="button" aria-controls="nav3" aria-expanded="false" aria-label="Toggle navigation" data-target="#nav3">
                        <svg class="icon">
                        <use xlink:href="/bootstrap-italia/dist/svg/sprite.svg#it-burger"></use>
                        </svg>
                    </button>
                    <div class="navbar-collapsable" id="nav3" style="display: none;">
                        <div class="overlay" style="display: none;"></div>
                        <div class="close-div sr-only">
                        <button class="btn close-menu" type="button"><span class="it-close"></span>close</button>
                        </div>
                        <div class="menu-wrapper">
                        <ul class="navbar-nav">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="false">
                                    <span>Filtro Tipo Documento</span>
                                    <svg class="icon icon-xs">
                                        <use xlink:href="svg/sprite.svg#it-expand"></use>
                                    </svg>
                                </a>
                                <div class="dropdown-menu">
                                    <div class="link-list-wrapper">
                                        <ul class="link-list" style="width:max-content;">
                                            <li>
                                                <h3 class="no_toc" id="heading-es-4">Tipo documento</h3>
                                            </li>
                                            <li><a class="list-item" onclick="showTipo(0,'Tutti');"><span>Tutti</span></a></li>
                                        <?php
                                            foreach($tipiReport as $tr){?>
                                            <li><a class="list-item" onclick="showTipo(<?=$tr['id']?>,'<?=$tr['descrizione']?>');"><span><?=$tr['descrizione']?></span></a></li>
                                        <?php }?>
                                                        
                                        </ul>
                                    </div>
                                </div>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="false">
                                    <span>Utente Inserimento</span>
                                    <svg class="icon icon-xs">
                                    <use xlink:href="/bootstrap-italia/dist/svg/sprite.svg#it-expand"></use>
                                    </svg>
                                </a>
                                <div class="dropdown-menu">
                                    <div class="link-list-wrapper">
                                        <ul class="link-list" style="width:max-content;">
                                           
                                            <li><a class="list-item" onclick="showUser(0);"><span>Tutti</span></a></li>
                                                        <?php
                                                            foreach($userIns as $ui){
                                                                $classUser=explode('@',$ui);
                                                                ?>
                                                                <li><a class="list-item" onclick="showUser('<?=$classUser[0]?>');" ><span><?=$ui?></span></a></li>
                                                            <?php }?>
                                                        
                                        </ul>
                                    </div>
                                </div>
                            </li>
                            <li class="nav-item "><a class="nav-link " id="selAll"onclick="unsendCkAll();"><span>Seleziona Tutti </span></a></li>
                            <li class="nav-item"><a class="nav-link" href="#"><span>Invia Pec Selezionate</span></a></li>
                            <li class="nav-item"><a class="nav-link" href="#"><span>Elimina richieste Selezionate </span></a></li>
                            
                            
                            
                        </ul>
                        </div>
                    </div>
                    </nav>
                </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-3">
                <em>Tipo documento:</em> <small id="info_tipo">Tutti</small>
            </div>
            <div class="col-3">
                <em>Utente Inserimento:</em> <small id="info_user">Tutti</small>
            </div>                                                       
         
        </div>












  <ul class="it-list">
    
    <?php

    if($pec){
        foreach($pec as $pa){
            if($pa['stato']=='B'){
                $tipo = getTipoReport($pa['tipo_report']);
                $istanza = getIstanza($pa['id_RAM']);
                $classUser=explode('@',$pa['user_ins']);
                ?>
            <li class="tiporeport_<?=$pa['tipo_report']?> userins_<?=$classUser[0]?>">
                <a class="it-has-checkbox" href="#">
                    <div class="form-check">
                        <input id="<?=$pa['id']?>" class="unsend"type="checkbox" >
                    <label for="<?=$pa['id']?>"></label>
                    </div>
                    <div class="it-right-zone">
                        <div class="col-7">
                            <span class="text"><?=$pa['id_RAM']?>/2020 <br><?=$istanza['ragione_sociale']?><em><?=$tipo?></em></span>
                        </div>
                        <div class="col-3">
                            <span class="text"><em>Inserita da:</em><p> <?=$pa['user_ins']?></p><p> <?=date("d/m/Y", strtotime($pa['data_ins']))?></p></span>
                        </div>
                        <div class="col-2">
                            <button type="button" class="btn btn-warning btn-sm" style="padding: 5px 12px;"title="Anteprima Documento"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></button>
                            <button type="button" class="btn btn-success btn-sm" style="padding: 5px 12px;"title="Invia Pec "><i class="fa fa-envelope" aria-hidden="true"></i></button>
                            <button type="button" class="btn btn-danger btn-sm" style="padding: 5px 12px;"title="Elimina Pec"><i class="fa fa-trash" aria-hidden="true"></i></button>

                        </div>

                    </div>
                </a>
            </li>

            <?php }else{?>
            <li>
                <a class="it-has-checkbox" href="#">
                   
                    <div class="it-right-zone"><span class="text">Non ci sono pec da inviare</span>
                    </div>
                </a>
            </li>
            <?php }
        }
    }?>
  </ul>
</div>