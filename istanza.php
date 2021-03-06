<?php
session_start();
require_once 'functions.php';

if(!isUserLoggedin()){

  header('Location:index.php');
  exit;
}

require_once 'model/istanze.php';
//$updateUrl = 'userUpdate.php';
$deleteUrl = 'controller/updateIstanze.php';
require_once 'headerInclude.php';
?>
<style>
.it-datepicker-wrapper {
    position: relative;
    margin-top: 50px;
}</style>
<div class="container my-4" style="max-width:90%">
 

    
      <?php
     
       require_once 'controller/displayIstanza.php' ;
          

      ?>
      
</div>    
<!--End Dashboard Content-->

<?php
    require_once 'view/template/footer.php';
?>

<script type="text/javascript"> 

$(document).ready(function() {
      $('.it-date-datepicker').datepicker({
            inputFormat: ["dd/MM/yyyy"],
            outputFormat: 'dd/MM/yyyy',
            
      });


});  
   
    function checkAlle(){
           
            
            var fa = document.getElementById("file_allegato");
            var f = fa.files[0]
            
            //var len = fa.files.length;
            console.log(f)
           // console.log(len)
            

                 

                  if (f.type==='application/pdf') {
                        if (f.size > 3388608 || f.fileSize > 3388608)
                  {
                  //show an alert to the user
                  
                  Swal.fire("Operazione Non Completata!", " L'allegato supera le dimensioni di 3MB", "warning");

                  //reset file upload control
                  fa.value = null;
                  }
                       
                  }else{
                        Swal.fire("Operazione Non Completata!", " L'allegato è del tipo errato. Selezionare un file PDF", "warning");
                        fa.value = null;
                  }
            
            
    }
    function checkAlleMail(){
           
            
           var fa = document.getElementById("file_allegato_mail");
           var f = fa.files[0]
           
           //var len = fa.files.length;
           console.log(f)
          // console.log(len)
           

                

                 if (f.type==='application/pdf') {
                       if (f.size > 3388608 || f.fileSize > 3388608)
                 {
                 //show an alert to the user
                 
                 Swal.fire("Operazione Non Completata!", " L'allegato supera le dimensioni di 3MB", "warning");

                 //reset file upload control
                 fa.value = null;
                 }
                      
                 }else{
                       Swal.fire("Operazione Non Completata!", " L'allegato è del tipo errato. Selezionare un file PDF", "warning");
                       fa.value = null;
                 }
           
           
    }
    $('#form_infovei').submit(function( event ) {
            id_RAM = <?=$i['id_RAM']?>;

            prog=$('#info_prog').val()
            
            idvei = $('#info_idvei').val()
            targa=$('#targa').val()
            marca=$('#marca').val()
            modello=$('#modello').val() 
            costo=$('#costo').val()
            tipo_veicolo = $('#info_tipo_veicolo').val()
            totdoc =getTotDoc(tipo_veicolo)
            tipo=$('#tipo_acquisizione option:selected').val()
                              $.ajax({
                                    type: "POST",
                                    url: "controller/updateIstanze.php?action=upVeicolo",
                                    data: {id:idvei,targa:targa,marca:marca,modello:modello,costo:costo,tipo:tipo},
                                    dataType: "html",
                                    success: function(msg)
                                    {     
                                          
                                          $('#targa_'+idvei).html(targa)
                                          $('#marca_'+idvei).html(marca)
                                          $('#modello_'+idvei).html(modello)
                                          deuro = parseFloat(costo).toLocaleString('it-IT', {style: 'currency', currency: 'EUR'});
                                          
                                          $('#costo_'+idvei).html(deuro)
                                          if(tipo=='01'){
                                          
                                                tipo="Acquisto";
                                                checkdoc = $('#c_t_d_'+tipo_veicolo+'_'+prog).html()
                                                checkdoc = parseInt(checkdoc)
                                                checkdoc =parseInt(totdoc)-1;
                                                
                                                $('#c_t_d_'+tipo_veicolo+'_'+prog).html(checkdoc)
                                                $('#btn_docmodal_'+idvei).attr('onclick','docmodal('+prog+','+tipo_veicolo+','+id_RAM+',\'01\');')
                                                
                                          }
                                          if(tipo=='02'){
                                                tipo='Leasing';
                                                checkdoc=parseInt(totdoc)
                                                
                                                $('#c_t_d_'+tipo_veicolo+'_'+prog).html(checkdoc)
                                                $('#btn_docmodal_'+idvei).attr('onclick','docmodal('+prog+','+tipo_veicolo+','+id_RAM+',\'02\');')
                                          }
                                          $('#tipo_acquisizione_'+idvei).html(tipo)
                                          alert='<div id="message2"style="position: fixed;z-index: 1000;right: 0;bottom: 0px;">' 
                                          alert+='<div id="almsg"class="alert alert-success" style="background-color: white;"role="alert">'
                                          alert+='Dati Veicolo Aggiornati</div></div>'  
                                          $( ".container" ).append(alert);
                                          $("#message2").delay(6000).slideUp(200, function() {
                                                $(".alert").alert('close')
                                          });
                                          $("#btn_up_"+prog+"_"+idvei).attr('onclick','infomodalup('+prog+','+idvei+');')
                                          html='<i class="fa fa-info" aria-hidden="true"></i> Aggiorna dati veicolo'
                                          $("#btn_up_"+prog+"_"+idvei).html(html)
                                          htmlck ='<i class="fa fa-check" style="color:green" aria-hidden="true"></i> Dati Veicolo presenti'
                                          $("#ckeck_info_vei_"+prog+"_"+idvei).html(htmlck)


                                    },
                                    error: function()
                                    {
                                    alert("Chiamata fallita, si prega di riprovare...");
                                    }

                              });

                              
            $("#infoModal").modal('hide');
            $(this)[0].reset();
            $("#tipo_acquisizione").val('').selectpicker("refresh");
            event.preventDefault();
            
    });
    function info_alle(){
           
            Swal.fire({ 
                  html:true,
                  title: "Caricamento in Corso",
                  type: "info"
            });
            note_ad = $('#note_admin').val();
            id = $('#id_allegato').val();
            stato_ad=$('#stato_allegato_admin').val()

            console.log(note_ad);
            $('#infoAllegato').modal('toggle');
            $.ajax({
                                    type: "POST",
                                    url: "controller/updateIstanze.php?action=upAlleAdmin",
                                    data: {id:id,note_admin:note_ad,stato_admin:stato_ad},
                                    dataType: "json",
                                    success: function(data){

                                          console.log(data);
                                          if(data.response){
                                          Swal.fire({ 
                                                
                                                title: "Dati Veicolo Aggiornati",
                                                type: "info"
                                          });
                                    
                                                 if(stato_ad=='A'){
                                                stato='<span class="badge badge-warning">In Lavorazione</span>';
                                               }
                                               if(stato_ad=='B'){
                                                stato='<span class="badge badge-success">Accettato</span>';
                                               }
                                               if(stato_ad=='C'){
                                                stato='<span class="badge badge-danger">Respinto</span>';
                                               }
                                               newstato= '#stato_admin_'+id;
                                          $('#stato_admin_'+id).html(stato);
                                          $('#note_admin_'+id).html(note_ad);
                                          }

                                          id = data.id_veicolo;
                                          ok= data.accettati;
                                          no= data.respinti;
                                          tot= data.totali;
                                          if(ok==tot){
                                                
                                                badgeA=' <span style="width: -webkit-fill-available;"class="badge badge-success">'+data.accettati+' di '+data.totali+'</span>'; 
                                               
                                               
                                          }else{
                                               
                                                badgeA=' <span style="width: -webkit-fill-available;"class="badge badge-warning">'+data.accettati+' di '+data.totali+'</span>';
                                          }
                                          if(no == 0){
                                               
                                                badgeB=' <span style="width: -webkit-fill-available;"class="badge badge-success">'+data.respinti+' di '+data.totali+'</span>'; 
                                          }else{
                                             
                                                badgeB=' <span style="width: -webkit-fill-available;"class="badge badge-danger">'+data.respinti+' di '+data.totali+'</span>';
                                          }
                                          $('#accettati_'+id).html( badgeA);
                                          $('#respinti_'+id).html( badgeB);
                                         
                                     }
            })
            
    };
    
    function newInt(tipo){
                
                id_RAM = <?=$i['id_RAM']?>;
                $.ajax({
                            type: "POST",
                            url: "controller/updateIstanze.php?action=newInt",
                            data: {id_RAM:id_RAM,tipo:tipo},
                            dataType: "json",
                            success: function(id){
                                $('#id_report').val(id);
                                $('#prev_btn').attr('onclick','prevRep('+id+');');
                                btn = 'saveRepBtn'+tipo;
                                $('#'+btn).attr('onclick','saveReport('+id+');');
                                

                            }
                }) 
            
    }
    
    function newRig(tipo){
                
                id_RAM = <?=$i['id_RAM']?>;
                $.ajax({
                            type: "POST",
                            url: "controller/updateIstanze.php?action=newInt",
                            data: {id_RAM:id_RAM,tipo:tipo},
                            dataType: "json",
                            success: function(id){
                                $('#id_report2').val(id);
                                $('#prev_btn2').attr('onclick','prevRep2('+id+');');
                                btn = 'saveRepBtn'+tipo;
                                $('#'+btn).attr('onclick','saveReport('+id+');');
                                

                            }
                }) 
            
    }
    function newVer(tipo){
                
                id_RAM = <?=$i['id_RAM']?>;
                $.ajax({
                            type: "POST",
                            url: "controller/updateIstanze.php?action=newInt",
                            data: {id_RAM:id_RAM,tipo:tipo},
                            dataType: "json",
                            success: function(id){
                                $('#id_report3').val(id);
                                $('#prev_btn3').attr('onclick','prevRep3('+id+');');
                                btn = 'saveRepBtn'+tipo;
                                $('#'+btn).attr('onclick','saveReport('+id+');');
                                

                            }
                }) 
            
    }
    function newIna(tipo){
                
                id_RAM = <?=$i['id_RAM']?>;
                $.ajax({
                            type: "POST",
                            url: "controller/updateIstanze.php?action=newInt",
                            data: {id_RAM:id_RAM,tipo:tipo},
                            dataType: "json",
                            success: function(id){
                                $('#id_report4').val(id);
                                $('#prev_btn4').attr('onclick','prevRep4('+id+');');
                                btn = 'saveRepBtn'+tipo;
                                $('#'+btn).attr('onclick','saveReport('+id+');');
                                

                            }
                }) 
            
    }
    function saveReport(id){
                prot_RAM=$('input[name="prot_RAM"]').val();
                data_prot=$('input[name="data_prot"]').val();
                $.ajax({
                            type: "POST",
                            url: "controller/updateIstanze.php?action=saveReport",
                            data: {id:id,prot_RAM:prot_RAM,data_prot:data_prot},
                            dataType: "json",
                            success: function(data){
                                
                                Swal.fire("Operazione Completata!", "Report Salvato", "info");
                                $('div[id^="reportModal"]').modal('hide');
                                td1=data.data_inserimento+'<br>'+data.user_ins;
                                td2=data.descrizione;
                                td3='Richiesta non inviata';
                                if(data.tipo_report==1){
                                    td4='<button type="button" onclick="prevRep('+data.id+');"class="btn btn-success btn-xs" title="Visualizza Documento" style="margin-right:10px;padding-left:12px;padding-right:12px;"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></button>'
                                    td4+='<button type="button" onclick="downRep('+data.id+');"class="btn btn-primary btn-xs" title="Scarica Documento" style="margin-right:10px;padding-left:12px;padding-right:12px;"><i class="fa fa-download" aria-hidden="true"></i></button>'
                                }else if(data.tipo_report==2){
                                    td4='<button type="button" onclick="prevRep2('+data.id+');"class="btn btn-success btn-xs" title="Visualizza Documento" style="margin-right:10px;padding-left:12px;padding-right:12px;"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></button>'
                                    td4+='<button type="button" onclick="downRep2('+data.id+');"class="btn btn-primary btn-xs" title="Scarica Documento" style="margin-right:10px;padding-left:12px;padding-right:12px;"><i class="fa fa-download" aria-hidden="true"></i></button>'
                              }else if(data.tipo_report==3){
                                    td4='<button type="button" onclick="prevRep3('+data.id+');"class="btn btn-success btn-xs" title="Visualizza Documento" style="margin-right:10px;padding-left:12px;padding-right:12px;"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></button>'
                                    td4+='<button type="button" onclick="downRep3('+data.id+');"class="btn btn-primary btn-xs" title="Scarica Documento" style="margin-right:10px;padding-left:12px;padding-right:12px;"><i class="fa fa-download" aria-hidden="true"></i></button>'

                              }else if(data.tipo_report==4){
                                    td4='<button type="button" onclick="prevRep4('+data.id+');"class="btn btn-success btn-xs" title="Visualizza Documento" style="margin-right:10px;padding-left:12px;padding-right:12px;"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></button>'
                                    td4+='<button type="button" onclick="downRep4('+data.id+');"class="btn btn-primary btn-xs" title="Scarica Documento" style="margin-right:10px;padding-left:12px;padding-right:12px;"><i class="fa fa-download" aria-hidden="true"></i></button>'

                              }
                              td4+='<button type="button" onclick="delRep('+data.id+');"class="btn btn-danger btn-xs" title="Elimina documento" style="margin-right:10px;padding-left:12px;padding-right:12px;"><i class="fa fa-trash" aria-hidden="true"></i></button>'
                                
                                
                                
                                
                                html= '<tr><td>'+td1+'</td><td>'+td2+'</td><td>'+td3+'</td><td>'+td4+'</td></tr>'
                                $("#reportTable > tbody").append(html);
                            }
                }) 

    }
    
    $('#tipo_report').change(function(){
      $('#veiNonConf').hide()
      $('#tabVeiNonConf >tbody').empty()
        tipo=$('#tipo_report option:selected').val()
        
        //console.log(tipo);
        if(tipo ==1){
                $('#reportModal').modal('toggle');
                newInt(tipo);
                id_RAM = <?=$i['id_RAM']?>;
                  $.ajax({
                    type: "POST",
                    url: "controller/updateIstanze.php?action=getDocR",
                    data: {id_RAM:id_RAM},
                    dataType: "json",
                    success: function(data){
                            console.log(data)
                            $.each(data , function (k,v){
                                 targa= v.targa
                                 tdoc= v.tipo_documento
                                 badge='<span class="badge badge-primary">'+targa+'</span><br>'
                                 tr='<tr><td>'+badge+'</td><td>'+tdoc+'</td></tr>'
                                 $('#veiNonConf').show()
                                 $('#tabVeiNonConf >tbody').append(tr)

                            })
                          

                    }
                  })         
        }
        if(tipo ==2){
                $('#reportModal2').modal('toggle');
                newRig(tipo);
        }
        if(tipo ==3){
                $('#reportModal3').modal('toggle');
                newVer(tipo);
               
        }
        if(tipo ==4){
                $('#reportModal4').modal('toggle');
                $("#tab_int4 > tbody").empty();
                newIna(tipo);
        }
        $('#tipo_report').val("")
        $('.bootstrap-select-wrapper select').selectpicker('refresh');

    });
    $('#tipo_integrazione').change(function(){
        tipo=$('#tipo_integrazione option:selected').val()
        $.ajax({
                    type: "POST",
                    url: "controller/updateIstanze.php?action=getTipoInt",
                    data: {tipo:tipo},
                    dataType: "json",
                    success: function(data){
                            console.log(data.frase)
                            $('#descrizione_integrazione').html(data.frase);
                            
                            $('#lab_des').addClass("active");
                            $('#lab_des').css("width","auto");
                            $('#des_int,#div_btn_add_int').show();

                    }
        })          
    });
    progtr=1;
    function addInt(){
        id_RAM= <?=$i['id_RAM']?>;
        id_report= $('#id_report').val();
        tipo = $('#tipo_integrazione option:selected').text()
        tipocod = $('#tipo_integrazione option:selected').val()
        //desc =  $('#descrizione_integrazione').html();
        desc =  $('#descrizione_integrazione').val();

        btn_edit='<button type="button" class="btn btn-primary btn-sm"> <i class="fa fa-pencil" aria-hidden="true"></i> </button>'
        btn_del = '<button type="button" class="btn btn-danger btn-sm"> <i class="fa fa-trash" aria-hidden="true"></i> </button>'
        html= '<tr><td>'+tipo+'</td><td id="desc_'+progtr+'">'+desc+'</td><td>'+btn_edit+btn_del+'</td></tr>'
        $("#tab_int > tbody").append(html);
        
        $("#div_tab_int").show();
        $('#des_int,#div_btn_add_int').hide();
        $.ajax({
                    type: "POST",
                    url: "controller/updateIstanze.php?action=newIntDett",
                    data: {id_RAM:id_RAM,id_report:id_report,prog:progtr,tipo:tipocod,descrizione:desc},
                    dataType: "json",
                    success: function(data){
                            console.log(data)
                            

                    }
        })  
        $("#div_tab_int").show();
        $('#des_int,#div_btn_add_int').hide();
        progtr++;    
        
        //alert(desc);
    }
    progtr2=1;
    function addInt2(){
        id_RAM= <?=$i['id_RAM']?>;
        id_report= $('#id_report2').val();
        tipo = 'Preavviso al Rigetto'
        tipocod = 2
        //desc =  $('#descrizione_integrazione').html();
        desc =  $('#motivazione').val();

        btn_edit='<button type="button" class="btn btn-primary btn-sm"> <i class="fa fa-pencil" aria-hidden="true"></i> </button>'
        btn_del = '<button type="button" class="btn btn-danger btn-sm"> <i class="fa fa-trash" aria-hidden="true"></i> </button>'
        html= '<tr><td>'+tipo+'</td><td id="desc_'+progtr2+'">'+desc+'</td><td>'+btn_edit+btn_del+'</td></tr>'
        $("#tab_int2 > tbody").append(html);
        
        $("#div_tab_int2").show();
        //$('#des_int,#div_btn_add_int').hide();
        $.ajax({
                    type: "POST",
                    url: "controller/updateIstanze.php?action=newIntDett",
                    data: {id_RAM:id_RAM,id_report:id_report,prog:progtr,tipo:tipocod,descrizione:desc},
                    dataType: "json",
                    success: function(data){
                            console.log(data)
                            $('#motivazione').val("");

                    }
        })  
        //$("#div_tab_int").show();
        //$('#des_int,#div_btn_add_int').hide();
        progtr2++;    
        
        //alert(desc);
    }
    progtr3=1;
    function addInt3(){
        id_RAM= <?=$i['id_RAM']?>;
        id_report= $('#id_report3').val();
        
        tipocod = 3
        //desc =  $('#descrizione_integrazione').html();
        //desc =  $('#motivazione').val();
        num_prot=$('#num_prot3').val()
        dat_prot=$('#dat_prot3').val()
        data_verbale=$('#data_verbale').val()


        btn_edit='<button type="button" class="btn btn-primary btn-sm"> <i class="fa fa-pencil" aria-hidden="true"></i> </button>'
        btn_del = '<button type="button" class="btn btn-danger btn-sm"> <i class="fa fa-trash" aria-hidden="true"></i> </button>'
       
        
        $("#div_tab_int3").show();
        //$('#des_int,#div_btn_add_int').hide();
        $.ajax({
                    type: "POST",
                    url: "controller/updateIstanze.php?action=newIntDett",
                    data: {id_RAM:id_RAM,id_report:id_report,prog:progtr,tipo:1,descrizione:num_prot},
                    dataType: "json",
                    success: function(data){
                            //console.log(data)
                            $('#num_prot').val("");
                            tipo = 'Numero protocollo Domanda Ammissione'
                            html= '<tr><td>'+tipo+'</td><td id="desc_'+progtr3+'">'+num_prot+'</td><td>'+btn_edit+btn_del+'</td></tr>'
                              $("#tab_int3 > tbody").append(html);
                              

                    }
        })
        progtr3++;  
        $.ajax({
              
                    type: "POST",
                    url: "controller/updateIstanze.php?action=newIntDett",
                    data: {id_RAM:id_RAM,id_report:id_report,prog:progtr,tipo:2,descrizione:dat_prot},
                    dataType: "json",
                    success: function(data){
                            //console.log(data)
                            $('#dat_prot').val("");
                            tipo='Data protocollo Domanda Ammissione';
                            html= '<tr><td>'+tipo+'</td><td id="desc_'+progtr3+'">'+dat_prot+'</td><td>'+btn_edit+btn_del+'</td></tr>'
                              $("#tab_int3 > tbody").append(html);

                    }
        }) 
        progtr3++; 
        $.ajax({
                    type: "POST",
                    url: "controller/updateIstanze.php?action=newIntDett",
                    data: {id_RAM:id_RAM,id_report:id_report,prog:progtr,tipo:3,descrizione:data_verbale},
                    dataType: "json",
                    success: function(data){
                            //console.log(data)
                            tipo='Data verbale'
                            $('#data_verbale').val("");
                            html= '<tr><td>'+tipo+'</td><td id="desc_'+progtr3+'">'+data_verbale+'</td><td>'+btn_edit+btn_del+'</td></tr>'
                            $("#tab_int3 > tbody").append(html);

                    }
        }) 
        progtr3++; 








        //$("#div_tab_int").show();
        //$('#des_int,#div_btn_add_int').hide();
        progtr3++;    
        
        //alert(desc);
    }
    progtr4=1;
    function addInt4(){
        id_RAM= <?=$i['id_RAM']?>;
        id_report= $('#id_report4').val();
        
        tipocod = 4
        //desc =  $('#descrizione_integrazione').html();
        //desc =  $('#motivazione').val();

        
       
       

      
        
        


        btn_edit='<button type="button" class="btn btn-primary btn-sm"> <i class="fa fa-pencil" aria-hidden="true"></i> </button>'
        btn_del = '<button type="button" class="btn btn-danger btn-sm"> <i class="fa fa-trash" aria-hidden="true"></i> </button>'
       
        
        $("#div_tab_int4").show();
        //$('#des_int,#div_btn_add_int').hide();
        num_prot=$('#num_prot_in').val()
        $.ajax({
                    type: "POST",
                    url: "controller/updateIstanze.php?action=newIntDett",
                    data: {id_RAM:id_RAM,id_report:id_report,prog:progtr,tipo:1,descrizione:num_prot},
                    dataType: "json",
                    success: function(data){
                            //console.log(data)
                            $('#num_prot_in').val("");
                            tipo = 'Numero protocollo Domanda Ammissione'
                            html= '<tr><td>'+tipo+'</td><td id="desc_'+progtr4+'">'+num_prot+'</td><td>'+btn_edit+btn_del+'</td></tr>'
                              $("#tab_int4 > tbody").append(html);
                              

                    }
        })
        progtr4++;  
        dat_prot=$('#dat_prot_in').val()
        $.ajax({
              
                    type: "POST",
                    url: "controller/updateIstanze.php?action=newIntDett",
                    data: {id_RAM:id_RAM,id_report:id_report,prog:progtr,tipo:2,descrizione:dat_prot},
                    dataType: "json",
                    success: function(data){
                            //console.log(data)
                            $('#dat_prot_in').val("");
                            tipo='Data protocollo Domanda Ammissione';
                            html= '<tr><td>'+tipo+'</td><td id="desc_'+progtr4+'">'+dat_prot+'</td><td>'+btn_edit+btn_del+'</td></tr>'
                              $("#tab_int4 > tbody").append(html);

                    }
        }) 
        progtr4++; 
        data_verbale=$('#data_verbale_in').val()
        $.ajax({
                    type: "POST",
                    url: "controller/updateIstanze.php?action=newIntDett",
                    data: {id_RAM:id_RAM,id_report:id_report,prog:progtr,tipo:3,descrizione:data_verbale},
                    dataType: "json",
                    success: function(data){
                            //console.log(data)
                            tipo='Data verbale'
                            $('#data_verbale_in').val("");
                            html= '<tr><td>'+tipo+'</td><td id="desc_'+progtr4+'">'+data_verbale+'</td><td>'+btn_edit+btn_del+'</td></tr>'
                            $("#tab_int4 > tbody").append(html);

                    }
        }) 
        progtr4++;
        num_prot_rig = $('#num_prot_rig').val()
        $.ajax({
                    type: "POST",
                    url: "controller/updateIstanze.php?action=newIntDett",
                    data: {id_RAM:id_RAM,id_report:id_report,prog:progtr,tipo:4,descrizione:num_prot_rig},
                    dataType: "json",
                    success: function(data){
                            //console.log(data)
                            $('#num_prot_rig').val("");
                            tipo = 'Numero protocollo Preavviso di rigetto'
                            html= '<tr><td>'+tipo+'</td><td id="desc_'+progtr4+'">'+num_prot_rig+'</td><td>'+btn_edit+btn_del+'</td></tr>'
                              $("#tab_int4 > tbody").append(html);
                              

                    }
        })
        progtr4++;  
        data_prot_rig = $('#dat_prot_rig').val()
        $.ajax({
              
                    type: "POST",
                    url: "controller/updateIstanze.php?action=newIntDett",
                    data: {id_RAM:id_RAM,id_report:id_report,prog:progtr,tipo:5,descrizione:data_prot_rig},
                    dataType: "json",
                    success: function(data){
                            //console.log(data)
                            $('#dat_prot_rig').val("");
                            tipo='Data protocollo Preavviso di rigetto';
                            html= '<tr><td>'+tipo+'</td><td id="desc_'+progtr4+'">'+data_prot_rig+'</td><td>'+btn_edit+btn_del+'</td></tr>'
                              $("#tab_int4 > tbody").append(html);

                    }
        }) 
        progtr4++; 
        data_prot_pre = $('#dat_prot_pre').val()
        
        $.ajax({
                    type: "POST",
                    url: "controller/updateIstanze.php?action=newIntDett",
                    data: {id_RAM:id_RAM,id_report:id_report,prog:progtr,tipo:6,descrizione:data_prot_pre},
                    dataType: "json",
                    success: function(data){
                            //console.log(data)
                            tipo='Data nota Preavviso'
                            $('#dat_prot_pre').val("");
                            html= '<tr><td>'+tipo+'</td><td id="desc_'+progtr4+'">'+data_prot_pre+'</td><td>'+btn_edit+btn_del+'</td></tr>'
                            $("#tab_int4 > tbody").append(html);

                    }
        }) 
        progtr4++; 
        mot_ina=$('#mot_ina').val()
        $.ajax({
                    type: "POST",
                    url: "controller/updateIstanze.php?action=newIntDett",
                    data: {id_RAM:id_RAM,id_report:id_report,prog:progtr,tipo:7,descrizione:mot_ina},
                    dataType: "json",
                    success: function(data){
                            //console.log(data)
                            tipo='Motivazione di Inassibilità'
                            $('#mot_ina').val("");
                            html= '<tr><td>'+tipo+'</td><td id="desc_'+progtr3+'">'+mot_ina+'</td><td>'+btn_edit+btn_del+'</td></tr>'
                            $("#tab_int4 > tbody").append(html);

                    }
        }) 
        progtr4++; 








        //$("#div_tab_int").show();
        //$('#des_int,#div_btn_add_int').hide();
       
        
        //alert(desc);
    }

    $('#form_allegato_mail').submit(function(event){
        event.preventDefault();
        defaultRep = $("#defaultreportId").val();
        console.log(defaultRep);
        newRep=$("#file_allegato_mail").val();
            if(defaultRep){
                  tipo = $('#tipo_report_mail').val()
                  if(tipo ==1){
                        
                  }
                  formData = new FormData(this);
                  $.ajax({
                            
                              url: "controller/updateIstanze.php?action=newMail",
                              type:"POST",
                              data: formData,
                              dataType: 'json',
                              contentType: false,
                              cache: false,
                              processData:false,
                              
                              success: function(data){
                                    
                                    
                                    Swal.fire("Operazione Completata!", "ccorrettamente.", "success");
                              
                              }
                        })
            }
            else if(newRep){
                  var htmltext='<div class="progress"><div class="progress-bar" role="progressbar" id="progress-bar2"style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div></div>'
            
            
                  Swal.fire({ 
                        html:true,
                        title: "Caricamento in Corso",
                        html:htmltext,
                        type: "info",
                        allowOutsideClick:false,
                        showConfirmButton:false
                  });
                  formData = new FormData(this);
                  $.ajax({
                              xhr: function() {
                                    var xhr = new window.XMLHttpRequest();
                                    xhr.upload.addEventListener("progress", function(evt) {
                                    if (evt.lengthComputable) {
                                          var percentComplete = ((evt.loaded / evt.total) * 100);
                                          $("#progress-bar2").width(percentComplete + '%');
                                          
                                    }
                                    }, false);
                                    return xhr;
                              },
                              url: "controller/updateIstanze.php?action=newMail",
                              type:"POST",
                              data: formData,
                              dataType: 'json',
                              contentType: false,
                              cache: false,
                              processData:false,
                              beforeSend: function(){
                                    $("#progress-bar2").width('0%');
                                    $('#uploadStatus').html('<img src="images/loading.gif"/>');
                              },
                              error:function(){
                                    
                                    Swal.fire("Operazione Non Completata!", "Allegato non caricato.", "warning");
                              
                              },
                              success: function(data){
                                    
                                    
                                    Swal.fire("Operazione Completata!", "Mail generata correttamente.", "success");
                              
                              }
                        })

            }

    })
      $('#tipo_documento').change(function(){
            $('#campi_allegati').empty();
            tipo=$('#tipo_documento option:selected').val()
            $.ajax({
                                    type: "POST",
                                    url: "controller/updateIstanze.php?action=getTipDoc",
                                    data: {tipo:tipo},
                                    dataType: "json",
                                    success: function(results){     
                                          
                                          $.each(results,function(k,v){
                                                
                                                required= v.richiesto
                                                if(required=="o"){
                                                      req = true
                                                }
                                                if(required =="f"){
                                                      req=false
                                                }
                                                
                                                var namecampo = v.nome_campo.replace(" ", "_");
                                                
                                                if (v.tipo_valore=='d'){
                                                field='<div class="it-datepicker-wrapper "><div class="form-group">'
                                                field+='<input onblur="testDate(this)" onkeypress="return event.charCode >= 47 && event.charCode <= 57" class="form-control it-date-datepicker" id="'+namecampo+'"name="'+namecampo+'" maxlength="10"type="text"  placeholder="inserisci la data">'
                                                field+='<label for="'+namecampo+'">'+v.nome_campo+'</label></div></div>'
                                                
                                                $('#campi_allegati').append(field)
                                                $( ".it-date-datepicker" ).datepicker({
                                                      inputFormat: ["dd/MM/yyyy"],
                                                      outputFormat: 'dd/MM/yyyy',
                                                });
                                                $("#"+namecampo).attr("required", req);
                                                }
                                                if (v.tipo_valore=='t'){
                                                field='<div class="form-group" style="margin-top: inherit;">'
                                                field+='<label for="'+namecampo+'">'+v.nome_campo+'</label>'
                                                field+='<input oninput="this.value = this.value.toUpperCase();" type="text" class="form-control" id="'+namecampo+'" name="'+namecampo+'" >'
                                                field+='</div>'
                                                
                                                $('#campi_allegati').append(field)
                                                $("#"+namecampo).attr("required", req);
                                                }
                                                if (v.tipo_valore=='i'){
                                                field='<label for="'+namecampo+'" class="input-number-label">'+v.nome_campo+'</label>'
                                                field+='<span class="input-number input-number-currency">'
                                                field+='<input type="number" id="'+namecampo+'" name="'+namecampo+'" step="any" value="0"  >'
                                                field+='</span>'
                                                
                                                $('#campi_allegati').append(field)
                                                $("#"+namecampo).attr("required", req);
                                                }
                                                if (v.tipo_valore=='n'){
                                                field='<label for="'+namecampo+'" class="input-number-label">'+v.nome_campo+'</label>'
                                                field+='<span class="input-number">'
                                                field+='<input type="number" id="'+namecampo+'" name="'+namecampo+'" step="any" value="0" >'
                                                field+='</span>'
                                                
                                                $('#campi_allegati').append(field)
                                                $("#"+namecampo).attr("required", req);
                                                }
                                                      

                                          })

                                          field='<div class="form-group" style="margin-top: inherit;">'
                                                field+='<label for="note_allegato">Note</label>'
                                                field+='<textarea  class="form-control"  rows="3" id="note_allegato" name="note_allegato"></textarea>'
                                                field+='</div>'
                                                $('#campi_allegati').append(field); 
                                                field='<div class="form-group">'
                                                field+='<label for="file_allegato" class="active">Documento</label>'
                                                field+='<input type="file" accept="application/pdf" class="form-control-file" id="file_allegato" onchange="checkAlle();" name="file_allegato"required><small>dimensioni max 3MB  - accettati solo PDF</small></div>'

                                                $('#campi_allegati').append(field) 


                                    },
                                    error: function()
                                    {
                                    alert("Chiamata fallita, si prega di riprovare...");
                                    }

                              });//ajax
            


      });
      $('#form_allegato').submit(function(event){
            $('#docModal').modal('toggle');
            var htmltext='<div class="progress"><div class="progress-bar" role="progressbar" id="progress-bar"style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div></div>'

            
            Swal.fire({ 
                  html:true,
                  title: "Caricamento in Corso",
                  html:htmltext,
                  type: "info",
                  allowOutsideClick:false,
                  showConfirmButton:false
            });
            
            event.preventDefault();
            tipo=$('#tipo_documento option:selected').attr("data-content")
            tipo= tipo.replace(/(<([^>]+)>)/ig,"");
            
            formData = new FormData(this);
            
                  $.ajax({
                        xhr: function() {
                              var xhr = new window.XMLHttpRequest();
                              xhr.upload.addEventListener("progress", function(evt) {
                                    if (evt.lengthComputable) {
                                          var percentComplete = ((evt.loaded / evt.total) * 100);
                                          $("#progress-bar").width(percentComplete + '%');
                                          
                                    }
                              }, false);
                              return xhr;
                        },
                        url: "controller/updateIstanze.php?action=newAllegato",
                        type:"POST",
                        data: formData,
                        dataType: 'json',
                        contentType: false,
                        cache: false,
                        processData:false,
                        beforeSend: function(){
                                    $("#progress-bar").width('0%');
                                    $('#uploadStatus').html('<img src="images/loading.gif"/>');
                              },
                              error:function(){
                              
                                    Swal.fire("Operazione Non Completata!", "Allegato non caricato.", "warning");
                              
                              },
                        success: function(data){
                              
                              
                                    Swal.fire("Operazione Completata!", "Allegato caricato correttamente.", "success");
                              tipoalle=data.tipo_veicolo
                              progalle=data.progressivo
                              checkDocVei(tipoalle,progalle)
                              data_ins=convData(data.data_agg)
                              ora_ins= convOre(data.data_agg)
                              //tipo_vei= formData.get('doc_idvei')
                              buttonA='<button type="button" onclick="infoAlle('+data.id+');"class="btn btn-warning btn-xs" title="Visualizza Info Allegato"style="padding-left:12px;padding-right:12px;"><i class="fa fa-list" aria-hidden="true"></i></button>'
                              buttonB='<button type="button" onclick="window.open(\'allegato.php?id='+data.id+'\', \'_blank\')"title="Vedi Documento"class="btn btn-xs btn-primary " style="padding-left:12px;padding-right:12px;"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></button>'
                              buttonC='<a type="button" href="download.php?id='+data.id+'" download title="Scarica Documento"class="btn btn-xs btn-success " style="padding-left:12px;padding-right:12px;"><i class="fa fa-download" aria-hidden="true"></i> </a>'
                              buttonD='<button type="button" onclick="delAll('+data.id+','+tipoalle+','+progalle+',this)"title="Elimina Documento"class="btn btn-xs btn-danger " style="padding-left:12px;padding-right:12px;"><i class="fa fa-trash" aria-hidden="true"></i></button>'

                              
                              
                              
                              row='<tr><td>'+tipo+'</td><td>'+data_ins+' '+ora_ins+'</td><td>'+data.note+'</td><td>'+buttonA+' '+buttonB+' '+buttonC+' '+buttonD+'</td></tr>'
                              $('#tab_doc_'+tipoalle+'_'+progalle+' > tbody:last-child').append(row);
                              
                        }
                  })

      })
      $('#form_allegato_mag').submit(function(event){
            $('#docMaggiorazione').modal('toggle');
            var htmltext='<div class="progress"><div class="progress-bar" role="progressbar" id="progress-bar"style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div></div>'

                  Swal.fire({ 
                        html:true,
                        title: "Caricamento in Corso",
                        html:htmltext,
                        type: "info",
                        allowOutsideClick:false,
                        showConfirmButton:false
                  });
                  
            event.preventDefault();
            tipo=$('#tipo_alle').val()
            
            formData = new FormData(this);
            
                  $.ajax({
                              xhr: function() {
                              var xhr = new window.XMLHttpRequest();
                              xhr.upload.addEventListener("progress", function(evt) {
                                    
                                    if (evt.lengthComputable) {
                                          var percentComplete = ((evt.loaded / evt.total) * 100);
                                    
                                          $("#progress-bar").width(percentComplete + '%');
                                          
                                    }
                              }, false);
                              return xhr;
                              },
                        url: "controller/updateIstanze.php?action=newAllegatoMag",
                        type:"POST",
                        data: formData,
                        dataType: 'json',
                        contentType: false,
                        cache: false,
                        processData:false,
                        beforeSend: function(){
                                    $("#progress-bar").width('0%');
                                    $('#uploadStatus').html('<img src="images/loading.gif"/>');
                              },
                              error:function(){
                              
                                    Swal.fire("Operazione Non Completata!", "Allegato non caricato correttamente.", "warning");
                              
                              },
                        success: function(data){
                                                            
                                    Swal.fire("Operazione Completata!", "Allegato caricato correttamente.", "success");
                              
                              
                              data_ins=convData(data.data_agg)
                              $('#data_'+tipo).html(data_ins)
                              $('#upload_'+tipo).hide()
                              $('#download_'+tipo).show()
                              $('#open_'+tipo).attr("onclick","window.open('allegato.php?id="+data.id+"', '_blank')");
                              $('#del_'+tipo).attr("onclick","delAlle("+data.id+",this);");
                              $('#down_'+tipo).attr("href","download.php?id="+data.id)
                              //id_table= formData.get('doc_idvei')
                              $('#file_allegato').val(null);
                              
                              
                              
                        }
                  })

      })
      $('#docModal').on('hidden.bs.modal', function (e) {
            $('#campi_allegati').empty();
      })
      $('#infoAllegato').on('hidden.bs.modal', function (e) {
            $('.modal-backdrop').css('z-index',1040);          
      }) 
      $('#istruttoriaModal').on('hidden.bs.modal', function (e) {
            $('.modal-backdrop').css('z-index',1040);
            
      })  
      $('#nav-3').on('shown.bs.tab', function (e) {
            var target = $(e.target).attr("href") // activated tab
                  alert(target);
      });
      $('#annForm').on('submit',function(e){
            e.preventDefault();
            formData = $(this).serialize();
                Swal.fire({
                  title: 'Vuoi annullare l\'istanza?',
                  text: "Non potrai più riattivarla",
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'SI Conferma annullamento!',
                  cancelButtonText: 'NO, Esci senza Annullare!'
                  }).then((result) => {
                        if (result.isConfirmed) {
                              $.ajax({
                                    url: "controller/updateIstanze.php?action=annIstanza",
                                    data: formData,
                                    dataType: "json",
                                    success: function(results){      
                                          if(results==true)
                                          {
                                                Swal.fire({
                                                      title:  'Annullata!',
                                                      text:  'L\'istanza è stata annullata correttamente.',
                                                      icon: 'success'
                                                }).then(()=>{
                                                      location.reload();
                                                })       
                                          }
                                    }
                              })
                        }else{
                              $('#offModal').modal('toggle')
                        }
                  })

      })
      function getTotDoc(tipo){
            $.ajax({
                        type: "POST",
                        url: "controller/updateIstanze.php?action=countDocVeicolo",
                        data: {tipo_veicolo:tipo},
                        dataType: "json",
                        success: function(data){
                            
                             totdoc = parseInt(data)
                             
                              return totdoc;
                            
                                                          
                        }
                       
                  })

               //   return totdoc;

      }
      function getCampo(cod){
            $.ajax({
                        type: "POST",
                        url: "controller/updateIstanze.php?action=getInfoCampo",
                        data: {cod:cod},
                        dataType: "json",
                        success: function(data){
                              return data
                        }
                  })


      }
      function infoVeiIstr(id){
            note = $('#info_note_admin').html();
            stato =$('#info_stato_admin').text();

            contr=$('#info_contr').val()
            pmi=$('#info_contr_pmi').val()
            rete=$('#info_contr_rete').val()
            $('#contr_up').val(contr)
            $('#contr_up_pmi').val(pmi)

            $('#contr_up_rete').val(rete)
            $('#note_istruttoria').val(note);
            if(stato=="In Lavorazione"){
                  stato='A';
            }
            if(stato=="Accettata"){
                  stato='B';
            }
            if(stato=="Rigettata"){
                  stato='C';
            }
            

            console.log(stato);
           
            $('#stato_istruttoria').val(stato);
            $('.bootstrap-select-wrapper select').selectpicker('refresh');
            $('#istruttoriaModal').modal('toggle');
            $('#istruttoriaModal').css("z-index", parseInt($('.modal-backdrop').css('z-index'))+100);
            $('.modal-backdrop').css('z-index',1050);
            $('.modal-backdrop').css('opacity', 0.4)
            $('#id_veicolo').val(id);
          
            $('#btn_info_istr').attr('onclick','upIstr('+id+')');
      }
      function upIstr(id){
            note=$('#note_istruttoria').val()
            stato=$('#stato_istruttoria option:selected').val();
            $.ajax({
                        type: "POST",
                        url: "controller/updateIstanze.php?action=upIstruttoria",
                        data: {id:id,note_admin:note,stato_admin:stato},
                        dataType: "json",
                        success: function(data){
                              Swal.fire({ 
                                                
                                                title: "Dati Veicolo Aggiornati",
                                                type: "info"
                                          });
                              $('#info_note_admin').html(note)
                              if(stato=='A'){
                                                stato='<span class="badge badge-warning">In Lavorazione</span>';
                                               }
                                               if(stato=='B'){
                                                stato='<span class="badge badge-success">Accettata</span>';
                                               }
                                               if(stato=='C'){
                                                stato='<span class="badge badge-danger">Rigettata</span>';
                                               }
                                               $('#info_stato_admin').html(stato)
                                               $('#stato_istruttoria_'+id).html(stato)
                                               $('#istruttoriaModal').modal('toggle');
                        }
            })

      }
      function infoAlle(id){
            const formatter = new Intl.NumberFormat('it-IT', {
                  style: 'currency',
                  currency: 'EUR',
                  minimumFractionDigits: 2
            })
            $('#infoAllegato').modal('toggle');
            $('#infoAllegato').css("z-index", parseInt($('.modal-backdrop').css('z-index'))+100);
            $('.modal-backdrop').css('z-index',1050);

            $('#info_tab_alle tbody').empty();
            $('#upinfoalle').empty();
            $.ajax({
                        type: "POST",
                        url: "controller/updateIstanze.php?action=getAllegato",
                        data: {id:id},
                        dataType: "json",
                        success: function(data){
                              //console.log(data);
                              test = $.parseJSON(data['allegato'].json_data)
                             //console.log(test);
                              $.each(test, function(k, v) {
                                  
                                    campo = k.split("_");
                                    console.log(campo)
                                   /* campo= capitalizeFirstLetter(campo[0])*/
                                    if (campo[1]) {
                                          campo= capitalizeFirstLetter(campo[0])+' '+ capitalizeFirstLetter(campo[1])
                                    }
                                    console.log(campo)
                                    if(campo=="Importo "){
                                          v = formatter.format(v);

                                    }
                                    if(campo=="Tipo Documento"){
                                          v = data['allegato'].tipo_documento;

                                    }
                                    $('#info_tab_alle').append('<tr><td>'+campo+'</td><td>'+v+'</td></tr>');
                                    

                              })
                              view = '<button type="button" onclick="window.open(\'allegato.php?id='+id+'\', \'_blank\')" title="Vedi Documento"class="btn btn-xs btn-primary " style="padding-left:12px;padding-right:12px;"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></button>'
                              if(data['allegato'].note_admin==null){
                                    data['allegato'].note_admin = '';
                              }
                              down ='<a type="button" href="download.php?id='+id+'" download title="Scarica Documento"class="btn btn-xs btn-success " style="padding-left:12px;padding-right:12px;"><i class="fa fa-download" aria-hidden="true"></i> </a>'
                              stato_istanza = '<div class="bootstrap-select-wrapper" style="margin-top:30px;"><label>Stato Lavorazione</label><select id="stato_allegato_admin" nome="stato_allegato_admin "title="Seleziona Stato"><option value="A" style="background: #ffda73; color: #fff;">In Lavorazione</option><option value="B" style="background: #5cb85c; color: #fff;">Accettato</option><option value="C"style="background: #d9364f; color: #fff;">Rigettato</option></select></div>'
                              note_istanza = '<div class="form-group" style="margin-top:30px;"><textarea rows="4" class="form-control" id="note_admin" nome="note_admin"  placeholder="inserire note">'+data['allegato'].note_admin+'</textarea><label for="note_admin" class="active">Scrivi note</label></div>'      
                              $('#info_tab_alle').append('<tr><td>Scarica Allegato</td><td>'+down+'</td></tr>');
                              $('#info_tab_alle').append('<tr><td>Visualizza allegato</td><td>'+view+'</td></tr>');
                              $form = $("<form method='post' id='info_alle_modal'></form>");
                              id_alle="<input type='hidden' id='id_allegato' name='id_allegato' value='"+id+"'>";
                              $form.append(id_alle);
                              $form.append(note_istanza);
                              $form.append(stato_istanza);
                            
                              $('#upinfoalle').append($form);
                              $('.bootstrap-select-wrapper select').selectpicker('render');
                              $('#stato_allegato_admin ').val(data['allegato'].stato_admin);
                              $('.bootstrap-select-wrapper select').selectpicker('refresh');
                             
                             


                            
                                                          
                        }
                  })



      } 
      function infoAlleIstanza(id){
            const formatter = new Intl.NumberFormat('it-IT', {
                  style: 'currency',
                  currency: 'EUR',
                  minimumFractionDigits: 2
            })
            $('#infoDichiarazioni').modal('toggle');
           $('#info_tab_alle_istanza,#upinfoalle_istanza').empty()
            $.ajax({
                        type: "POST",
                        url: "controller/updateIstanze.php?action=getAllegatoIstanza",
                        data: {id:id},
                        dataType: "json",
                        success: function(data){
                              console.log(data.allegato);
                              if(data.allegato.stato_admin=='A'||data.allegato.stato_admin==null){
                                    stato_admin = '<span class="badge badge-warning">In Lavorazione</span>';
                              }
                              if(data.allegato.stato_admin=='B'){
                                    stato_admin = '<span class="badge badge-success">Accettata</span>';
                              }
                              if(data.allegato.stato_admin=='C'){
                                    stato_admin = '<span class="badge badge-danger" >Rigettata</span>';
                              }
                              view = '<button type="button" onclick="window.open(\'allegato.php?id='+id+'\', \'_blank\')" title="Vedi Documento"class="btn btn-xs btn-primary " style="padding-left:12px;padding-right:12px;"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></button>'
                              down ='<a type="button" href="download.php?id='+id+'" download title="Scarica Documento"class="btn btn-xs btn-success " style="padding-left:12px;padding-right:12px;"><i class="fa fa-download" aria-hidden="true"></i> </a>'

                              tr ='<tr><td>Tipo documento</td><td>'+data.allegato.tipo_doc+'</td></tr>'
                              tr +='<tr><td>Stato Documento</td><td id="stato_doc_istanza_'+id+'">'+stato_admin+'</td></tr>'
                              tr += '<tr><td>Visualizza Allegato</td><td>'+view+'</td></tr>'
                              tr += '<tr><td>Scarica Allegato</td><td>'+down+'</td></tr>'
                              $('#info_tab_alle_istanza').append(tr)
                              stato_istanza = '<div class="bootstrap-select-wrapper" style="margin-top:30px;"><label>Stato Lavorazione</label><select id="stato_allegato_admin_istanza" nome="stato_allegato_admin "title="Seleziona Stato"><option value="A" style="background: #ffda73; color: #fff;">In Lavorazione</option><option value="B" style="background: #5cb85c; color: #fff;">Accettato</option><option value="C"style="background: #d9364f; color: #fff;">Rigettato</option></select></div>'
                              if(data.allegato.note_admin==null){
                                    data.allegato.note_admin = '';
                              }
                              note_istanza = '<div class="form-group" style="margin-top:30px;"><textarea rows="4" class="form-control" id="note_admin_istanza" nome="note_admin"  placeholder="inserire note">'+data.allegato.note_admin+'</textarea><label for="note_admin" class="active">Scrivi note</label></div>'      
                              form = $("<form method='post' id='info_alle_modal_istanza'></form>");
                              id_alle="<input type='hidden' id='id_allegato_istanza' name='id_allegato' value='"+id+"'>";
                              tipo="<input type='hidden' id='tipo_allegato_istanza' name='id_allegato' value='"+data.allegato.tipo_documento+"'>";
                              form.append(id_alle);
                              form.append(tipo);
                              form.append(note_istanza);
                              form.append(stato_istanza);
                            
                              $('#upinfoalle_istanza').append(form);
                              $('.bootstrap-select-wrapper select').selectpicker('render');
                              $('#stato_allegato_admin_istanza ').val(data.allegato.stato_admin);
                              $('.bootstrap-select-wrapper select').selectpicker('refresh');
                              
                             
                             


                            
                                                          
                        }
                  })



      }
      function info_alle_istanza(){
           
          
           note_ad = $('#note_admin_istanza').val();
           id = $('#id_allegato_istanza').val();
           stato_ad=$('#stato_allegato_admin_istanza').val()
           tipo= $('#tipo_allegato_istanza').val()
           
           
           //$('#infoAllegato').modal('toggle');
           $.ajax({
                                   type: "POST",
                                   url: "controller/updateIstanze.php?action=upAlleAdminIstanza",
                                   data: {id:id,note_admin:note_ad,stato_admin:stato_ad,tipo_documento:tipo},
                                   dataType: "json",
                                   success: function(data){

                                         console.log(data);
                                         if(data){
                                         Swal.fire({ 
                                               
                                               title: "Dati Istanza Aggiornati",
                                               icon: "info"
                                         });
                                   
                                             
                                      
                                         }

                                      
                                        
                                    }
           })
           
      };     
      function infomodal(prog,id){
         $('#form_infovei')[0].reset();
         $("#tipo_acquisizione").html('<option value="01">Acquisto</option><option value="02">Leasing</option>');
         $("#tipo_acquisizione").prop('required',true);
         $(".bootstrap-select-wrapper select").selectpicker("refresh");
         getInfoVei2(id);
            //alert(id);
            $("#infoModal").modal("toggle");
            $("#info_idvei").val(id);
            $("#info_prog").val(prog);

      } 
      function infomodalup(prog,id){

            //alert(id);
            $("#infoModal").modal("toggle");
            getInfoVei(id);
            $("#info_prog").val(prog);
            $("#info_idvei").val(id);
            

      } 
      function docmagmodal(id,tipodoc){
           
            $("#docMaggiorazione").modal("toggle");
            $("#tipo_doc_mag").val(tipodoc);
            $("#tipo_alle").val(id);
            tipo = $('#tipo_magg_'+id).text();
        
            $('#tipo_documento_magg').val(tipo);



            
      }
      function getInfoVei(id){
                  $.ajax({
                        type: "POST",
                        url: "controller/updateIstanze.php?action=getInfoVei",
                        data: {id:id},
                        dataType: "json",
                        success: function(data){
                           
                              $('#targa').val(data.targa)
                              $('#marca').val(data.marca)
                              $('#modello').val(data.modello)
                              $('#costo').val(data.costo)
                              $('#info_tipo_veicolo').val(data.tipo_veicolo)
                              
                              $('.bootstrap-select-wrapper select').val(data.tipo_acquisizione);
                              $('.bootstrap-select-wrapper select').selectpicker('render');
                              
                            
                                                          
                        }
                  })


      }
      function getInfoVei2(id){
            //$(".selinfo option").remove();
            //$('.selinfo select').selectpicker('refresh')
                  $.ajax({
                        type: "POST",
                        url: "controller/updateIstanze.php?action=getInfoVei",
                        data: {id:id},
                        dataType: "json",
                        success: function(data){
                            
                            
                              $('#info_tipo_veicolo').val(data.tipo_veicolo)
                              
                             
                            
                                                          
                        }
                  })


      } 
      function docmodal(prog,tipovei,istanza,tipoac){
            id_RAM =istanza;
          
            $(".seldoc option").remove();
            $('.seldoc select').selectpicker('refresh')
           
            $("#docModal").modal("toggle");
           
            $("#tipo_veicolo").val(tipovei);
            $("#progressivo").val(prog);
                  $.ajax({
                        type: "POST",
                        url: "controller/updateIstanze.php?action=getDocVei",
                        data: {tipovei:tipovei,id_RAM:id_RAM,progressivo:prog},
                        dataType: "json",
                        success: function(data){
                            $('#tr_not').hide()
                              $.each(data, function(k,v){
                                   
                                    tip=v.codice_tipo_documento
                                 
                                    tipoDoc(tip,tipoac)


                              })
                                                          
                        }
                  })
                
                

                  
      } 
      /////////////////////    
      function tipDoc(tip){
       $('#row_doc').empty();

           
           $.ajax({

                 url:"controller/updateIstanze.php?action=getTipDoc",
                 type:"POST",
                 data:{tip:tip},
                 dataType:"json",
                 success:function(data){
                       $.each(data, function(k,v){
                              
                              tipo=v.tipo_valore;
                              if (tipo=="d"){
                                    type='date';
                                    id="data_documento";
                                    input = '<div class="form-group">';
                                    input +='<div class="it-datepicker-wrapper">'
                                    input +='<div class="form-group">'
                                    input +='<input class="form-control it-date-datepicker" id="'+id+'" type="text" placeholder="inserisci la data in formato gg/mm/aaaa">'
                                    input +='<label for="'+id+'">'+v.nome_campo+'</label>'
                                    input +='</div>'
                                    input +='</div>'
                                    input +='</div>'
                                    

                              }
                              if (tipo=="n"){
                                    type='number';
                                    id="numero_documento";
                              }
                              if (tipo=="i"){
                                    type='number';
                                    id="importo_documento";

                                    
                                    input='<div class="w-50 mt-5">'
                                   // input ='<label for="'+id+'" class="input-number-label">'+v.nome_campo+'</label>';
                                   // input +=' <span class="input-number input-number-currency">'
                                   // input +='<input type="'+type+'" class="form-control" id="'+id+'" min="0" value="0"></span>';
                                    
                                    // ';
                                    input+='<label for="'+id+'" class="input-number-label">'+v.nome_campo+'</label>'
                                    input +='<span class="input-number input-number-currency">'
                                    input +='<input type="number" id="'+id+'" name="'+id+'" step="any" value="0.00" min="0">'
                                   
                                    //input +='<button class="input-number-add">'
                                   // input +='<span class="sr-only">Aumenta valore Euro</spstep="any"an>'
                                    ////input +='</button>'
                                    //input +='<button class="input-number-sub">'
                                    //input +='<span class="sr-only">Diminuisci valore Euro</span>'
                                    //input +='</button>'
                                    input +='</span>'
                                    input +='</div>'
                                   
                              }
                              if (tipo=="t"){
                                    type='text';
                                    id="testo_documento";
                                    input = '<div class="form-group">';
                                    input +='<input type="'+type+'" class="form-control" id="'+id+'">';
                                    input +='<label for="'+id+'">'+v.nome_campo+'</label>';
                                    input +=' </div>';
                              }
                              $('.it-date-datepicker').datepicker({
                                          inputFormat: ["dd/MM/yyyy"],
                                          outputFormat: 'dd/MM/yyyy',
                                    });
                              $('#row_doc').append(input);
                       });

                 }
           })
      }
      function tipoDoc(tipo,tipoac){
            //id_RAM = '<?=$i['id_RAM']?>';
            $.ajax({
                        type: "POST",
                        url: "controller/updateIstanze.php?action=getTipoDoc",
                        data: {tipo:tipo},
                        dataType: "json",
                        success: function(data){
                              $.each(data, function(k,v){
                                   if(v.tdoc_codice=='9' && tipoac=='01'){
                                 
                                   }else{
                                    $('.seldoc select').append('<option data-subtext="Documento già inserito" data-content="' + v.tdoc_descrizione + '" value="' + v.tdoc_codice + '"></option>');
                                    $('.seldoc select').selectpicker('refresh')
                                   }
                              })
                        }
                                                          
                        
                  })
                  


      
      }    
      function convData(isodata){
            newdata = new Date(isodata);
            newgiorno =newdata.getDate()
            if(newgiorno<10){
                  newgiorno="0"+newgiorno
            }
            newmese=newdata.getMonth()+1;
            if(newmese<10){
                  newmese="0"+newmese
            }
            newanno=newdata.getFullYear();
            return newgiorno+'/'+newmese+'/'+newanno;
      }
      function convOre(isodata){
            newdata = new Date(isodata);
            ore = newdata.getHours();
            minuti = newdata.getMinutes();
            
            return ore+':'+minuti;
      }
      function delAlle(ida,elem){
           
            div_down= elem.parentNode.id;
            div_up=div_down.split("_");
           
            div_up = div_up[1];
           
            Swal.fire({
                  title: 'Vuoi eliminare l\'allegato?',
                  text: "Non potrai più recuperarlo",
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'SI Eliminalo!',
                  cancelButtonText: 'NO, Annulla!'
                  }).then((result) => {
                        if (result.isConfirmed) {
                              $.ajax({
                                    url: "controller/updateIstanze.php?action=delAllegato",
                                    data: {id:ida},
                                    dataType: "json",
                                    success: function(results){
                                         
                                          if(results)
                                          {
                                                $('#upload_'+div_up).show();
                                                $('#'+div_down).hide()
                                                $('#data_'+div_up).text("Allegato non Caricato")
                                                Swal.fire(
                                                      'Eliminato!',
                                                      'L\'allegato è stato eliminato correttamente.',
                                                      'success'
                                                )
                                          }
                                         
                                    }

                              })


                        }
                  })
      }
      function delAll(ida,tipo,prog,elem){
                 Swal.fire({
                  title: 'Vuoi eliminare l\'allegato?',
                  text: "Non potrai più recuperarlo",
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'SI Eliminalo!',
                  cancelButtonText: 'NO, Annulla!'
                 }).then((result) => {
                       if (result.isConfirmed) {
                             $.ajax({
                                   url: "controller/updateIstanze.php?action=delAllegato",
                                   data: {id:ida},
                                   dataType: "json",
                                   success: function(results){
                                        
                                         if(results)
                                         {
                                          checkDocVei(tipo,prog);  
                                          $(elem).closest('tr').remove();
                                               Swal.fire(
                                                     'Eliminato!',
                                                     'L\'allegato è stato eliminato correttamente.',
                                                     'success'
                                               )
                                         }
                                        
                                   }

                             })


                       }
                 })
      }
      function checkDocVei(tipo,prog){
          
            checkvp=$('#c_p_d_'+tipo+'_'+prog).html()
            checkvt=$('#c_t_d_'+tipo+'_'+prog).html()
           
            
            checkcatp= $('#ch_p_'+tipo).html()
            checkcatt= $('#ch_t_'+tipo).html()

            checkvp = parseInt(checkvp)
            checkvt= parseInt(checkvt)
            
            checkcatp= parseInt(checkcatp)
            checkcatt= parseInt(checkcatt)

            if(checkvp == checkvt){
                  docvei = true
            }else{
                  docvei = false
            }
            
            if(checkcatp==checkcatt){
                  catvei = true
            }else{
                  catvei = false
            }
           
            id_RAM =<?=$i['id_RAM']?>,
            
            $.ajax({
                        type: "POST",
                        url: "controller/updateIstanze.php?action=checkDoc",
                        data: {id_RAM:id_RAM,tipo_veicolo:tipo,progressivo:prog},
                        dataType: "json",
                        success: function(data){
                              if(data.rott==true){ 
                                   $('#c_p_d_R_'+tipo+'_'+prog).html(data.n_R)
                              }
                              if(data.n==checkvt){
                                    ic="check"
                                    color="green"
                                    if(catvei == false){
                                          checkcatp++ ;
                                          $('#ch_p_'+tipo).html(checkcatp)
                                          if(checkcatp==checkcatt){

                                                $('#ch_i_'+tipo).removeClass();
                                                $('#ch_i_'+tipo).addClass("fa fa-check");
                                                $('#ch_i_'+tipo).css("color", "green");

                                          }    

                                    }
                                    
                              }else{
                                    ic="ban";
                                    color="red";
                                    if(docvei == true){
                                          checkcatp = checkcatp-1; 
                                        
                                          $('#ch_p_'+tipo).html(checkcatp);
                                          $('#ch_i_'+tipo).removeClass();
                                                $('#ch_i_'+tipo).addClass("fa fa-ban");
                                                $('#ch_i_'+tipo).css("color", "red");
                                    }
                                    
                              }
                              
                              icon='<i class="fa fa-'+ic+'" style="color:'+color+';"aria-hidden="true"></i> Documenti veicoli caricati <b id="c_p_d_'+tipo+'_'+prog+'">'+data.n+'</b> di  <b id="c_t_d_'+tipo+'_'+prog+'">'+checkvt+'</b>'
                              $('#check_vei_'+tipo+'_'+prog).html(icon);
                             
                              
                            
                                                          
                        }
                  })


      }
      function capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
      }
      function closeRend(id_ram){
            check=checkIstanza();
          
            textAlert="";
            if(check==0){
                  Swal.fire({
                  
                  title: 'Vuoi chiudere la Rendicontazione?',
                  html: "Non potrai più aggiornarla",
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'SI Chiudila!',
                  cancelButtonText: 'NO, Annulla!'
                  }).then((result) => {
                        if (result.isConfirmed) {
                              $.ajax({
                                    url: "controller/updateIstanze.php?action=closeRend",
                                    data: {id_ram:id_ram},
                                    dataType: "json",
                                    success: function(results){
                                         
                                          if(results)
                                          {
                                                Swal.fire({
                                                                  allowOutsideClick:false,

                                                                  title: 'Rendicontazione Chiusa!',
                                                                  html:'La rendicontazione è stata chiusa correttamente.',
                                                                  icon:'success'
                                                            }).then((result) => {
                                                                               if (result.isConfirmed) {
                                                                                          location.href='home.php'
                                                                              }
                                                                  })
                                          }
                                        
                                    }

                              })


                        }
                  })

            }else{
             textAlert= check; 
             Swal.fire({
                  
                  title: 'La rendicontazione è incompleta! ',
                  html: textAlert,
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'SI Procedi!',
                  cancelButtonText: 'NO, Annulla!'
                  }).then((result) => {
                        if (result.isConfirmed) {
                              Swal.fire({
                              
                              title: 'Vuoi chiudere la Rendicontazione?',
                              html: "Non potrai più aggiornarla",
                              icon: 'warning',
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'SI Chiudila!',
                              cancelButtonText: 'NO, Annulla!'
                              }).then((result) => {
                                    if (result.isConfirmed) {
                                          $.ajax({
                                                url: "controller/updateIstanze.php?action=closeRend",
                                                data: {id_ram:id_ram},
                                                dataType: "json",
                                                success: function(results){
                                                
                                                      if(results)
                                                      {
                                                            Swal.fire({
                                                                  allowOutsideClick:false,

                                                                  title: 'Rendicontazione Chiusa!',
                                                                  html:'La rendicontazione è stata chiusa correttamente.',
                                                                  icon:'success'
                                                            }).then((result) => {
                                                                               if (result.isConfirmed) {
                                                                                          location.href='home.php'
                                                                              }
                                                                  })






                                                      }
                                                   
                                                }

                                          })


                                    }
                              })




                        }
                  })    

            }
           


      }
      function testDate(str) {
          
            data= str.value
            var t = data.match(/^(\d{2})\/(\d{2})\/(\d{4})$/);
            if(t === null)
            Swal.fire({ 
                //html:true,
                title: "Inserire una Data",
                text:"formato gg/mm/aaaa",
                icon: "warning"
                
            });
            var d = +t[1], m = +t[2], y = +t[3];

            // Below should be a more acurate algorithm
            if(m >= 1 && m <= 12 && d >= 1 && d <= 31) {
            return true;  
            }
            $('#'+str.id).val("");
            Swal.fire({ 
                //html:true,
                title: "Inserire la Data in modo corretto!",
                text:"formato gg/mm/aaaa",
                icon: "warning"
                
            });
      }   
      function checkIstanza(){

            veiok=0;
            veitot=0
           
            $("[id^=ch_p_]").each(function() {
                  value=$(this).text()
                      
                       value = parseFloat(value);
                       if(!isNaN(value) && value.length != 0) {
                        veiok += value;
                       }
   
                      
                   });
            $("[id^=ch_t_]").each(function() {
                  valuet=$(this).text()
                     
                       valuet = parseFloat(valuet);
                       if(!isNaN(valuet) && valuet.length != 0) {
                        veitot += valuet;
                       }
   
                      
                   });

                   if(veitot==veiok){
                         return 0;
                   }else{
                         return "<b>Hai inserito i documenti di "+veiok+" veicoli su "+ veitot+"!</b><br>Vuoi Procedere con la Chiusura?";
                   }
                 
      }
      function infovei(id,cat,tipo){
            const formatter = new Intl.NumberFormat('it-IT', {
                  style: 'currency',
                  currency: 'EUR',
                  minimumFractionDigits: 2
            })
                  $('#doctab tbody').empty();
                  $('#modalinfovei').modal('toggle');
                  $('#contributo').html("")
                 
                  $('#contr_pmi').html("")
                  $('#contr_rete').html("")
                  $('#info_contr').val("")
                  $('#info_contr_pmi').val("")
                  $('#info_contr_rete').val("")
                  $.ajax({
                        type: "POST",
                        url: "controller/updateIstanze.php?action=getInfoVei",
                        data: {id:id},
                        dataType: "json",
                        success: function(data){
                              $('#id_veicolo').val(data.id)
                              contr = parseFloat(data.val_contributo).toLocaleString('it-IT', {style: 'currency', currency: 'EUR'});

                              val_pmi = parseFloat(data.val_pmi).toLocaleString('it-IT', {style: 'currency', currency: 'EUR'});
                              val_rete = parseFloat(data.val_rete).toLocaleString('it-IT', {style: 'currency', currency: 'EUR'});
                              $('#info_contr').val(data.val_contributo)
                              $('#info_contr_pmi').val(data.val_pmi)
                              $('#info_contr_rete').val(data.val_rete)

                              $('#contributo').html(contr)
                              $('#contr_pmi').html(val_pmi)
                              $('#contr_rete').html(val_rete)

                              $('#info_targa').html(data.targa)
                              $('#info_marca').html(data.marca)
                              $('#info_modello').html(data.modello)
                              $('#info_note_admin').html(data.note_admin)
                              $('#btn_istr').attr('onclick','infoVeiIstr('+data.id+')');

                              
                              if(data.stato_admin=='A'||data.stato_admin==null){
                                    stato='<span class="badge badge-warning">In Lavorazione</span>';
                              }
                              if(data.stato_admin=='B'){
                                    stato='<span class="badge badge-success">Accettata</span>';
                              }
                              if(data.stato_admin=='C'){
                                   stato='<span class="badge badge-danger">Rigettata</span>';
                              }

                              $('#info_stato_admin').html(stato);
                              v = formatter.format(data.costo);
                              $('#info_costo').html(v)
                              if(data.tipo_acquisizione =="01"){
                                    tipoac="Acquisto";
                              }
                              if(data.tipo_acquisizione =="02"){
                                    tipoac="Leasing";
                              }
                              $('#info_tipo_acquisizione').html(tipoac);
                             cat='<span class="badge badge-danger" style="font-size:20px;width: -webkit-fill-available;">'+cat+'</span>';
                             tipo ='<span class="badge badge-secondary" style="font-size:20px;width: -webkit-fill-available;">'+tipo+'</span>';
                              $('#info_tipo_veicolo').html(tipo)
                              $('#info_cat_veicolo').html(cat)
                            
                             $.ajax({
                                    type: "POST",
                                    url: "controller/updateIstanze.php?action=getAllegati",
                                    data: {id_RAM:data.id_RAM,tipo_veicolo:data.tipo_veicolo,progressivo:data.progressivo},
                                    dataType: "json",
                                    success: function(alle){
                                        
                                         $.each(alle, function (k,v){
                                               if(v.stato_admin=='A'){
                                                stato='<span class="badge badge-warning">Da Accettare</span>';
                                               }
                                               if(v.stato_admin=='B'){
                                                stato='<span class="badge badge-success">Accettato</span>';
                                               }
                                               if(v.stato_admin=='C'){
                                                stato='<span class="badge badge-danger">Respinto</span>';
                                               }
                                               
                                                     note_ad=v.note_admin;
                                          
                                               
                                               buttonA='<button type="button" onclick="infoAlle('+v.id+');"class="btn btn-warning btn-xs" title="Visualizza Info Allegato"style="margin-right:10px;padding-left:12px;padding-right:12px;"><i class="fa fa-list" aria-hidden="true"></i></button>'
                                                buttonB='<button type="button" onclick="window.open(\'allegato.php?id='+v.id+'\', \'_blank\')"title="Vedi Documento"class="btn btn-xs btn-primary " style="padding-left:12px;padding-right:12px;margin-right:10px;"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></button>'
                                                buttonC='<a type="button" href="download.php?id='+v.id+'" download title="Scarica Documento"class="btn btn-xs btn-success " style="padding-left:12px;padding-right:12px;"><i class="fa fa-download" aria-hidden="true"></i> </a>'
                                               
                                              
                                        row = '<tr><td>'+v.tdoc_descrizione+'</td><td>'+v.note+'</td><td id="stato_admin_'+v.id+'">'+stato+'</td><td id="note_admin_'+v.id+'">'+note_ad+'</td><td>'+buttonA+''+buttonB+''+buttonC+'</td></tr>'            
                                                $('#doctab> tbody:last-child').append(row);

                                         })
                                          
                                    
                                                                  
                                    }
                              })
                            
                                                          
                        }
                  })


      }  
      function infomsg(id){

            $.ajax({
                  type: "POST",
                  url: "controller/updateComunicazioni.php?action=getMsg",
                  data: {id:id},
                  dataType: "json",
                  success: function(data){
                  console.log(data);
                  $('#msginfoModal').modal('toggle');
                  $('#id_info').html(data.id);
                  $('#data_ins_info').html(data.data_ins);
                  $('#tipo_info').html(data.tipo);
                  $('#testo_info').html(data.testo);
                  $('#stato_info').html(data.stato);
                  }                  
            })
      }
      function infomsgAd(id){

            $.ajax({
                  type: "POST",
                  url: "controller/updateComunicazioni.php?action=getMsg",
                  data: {id:id},
                  dataType: "json",
                  success: function(data){
                  console.log(data);
                  $('#msginfoModal').modal('toggle');
                  $('#id_info').html(data.id);
                  $('#data_ins_info').html(data.data_ins);
                  $('#tipo_info').html(data.tipo);
                  $('#testo_info').html(data.testo);
                  $('#stato_info').html(data.stato+' da '+data.user_info+' il '+data.data_info);
                  $('#gotomsg').attr('href','comunicazione.php?id='+data.id);
                  if(data.read_msg == 0){
                  $('#gotomsg').html('Prendi in carico');

                  }else{
                  $('#gotomsg').html('Vedi dettaglio');
                  }


                  
                  }
                                          
                  
            })


      }
      function infoCert(idRam,tipo,title){

           // alert(idRam+' tipo:'+tipo)
            $('#certModal')
            .find('.modal-title').text(title).end()  
            .modal('toggle');
            $.ajax({
                  type: "POST",
                  url: "controller/updateIstanze.php?action=checkCert",
                  data: {id_ram:idRam,tipo:tipo},
                  dataType: "json",
                  success: function(data){

                        //console.log(data);
                        $('#tipo_cert').val(tipo)
                        $('#note_check_cert').text(data.note)
                        $('#stato_check_cert').val(data.select);
                        $('#stato_check_cert').selectpicker('render');
                        $('#btnSaveCert').attr('onclick','saveCert('+idRam+',\''+tipo+'\');')
                        
                  
                              
                        
                        

                        
                        
                  }
           })
      }
      function saveCert(idRam,tipo){

            select=$('#stato_check_cert option:selected').val()
            note = $('#note_check_cert').val()
            $.ajax({
                  type: "POST",
                  url: "controller/updateIstanze.php?action=upCert",
                  data: {id_ram:idRam,tipo:tipo,select:select,note:note},
                  dataType: "json",
                  success: function(data){
                        console.log(data)
                      
                        $('#note_'+tipo).html(data.note)
                        $('#stato_'+tipo).html(data.stato_tipo)
                      
                        if(data){
                              $('#certModal').modal('toggle')

                        }
                      
                  
                              
                        
                        

                        
                        
                  }
           })
      }

</script>

</body>
</html>    