@extends('layouts/base')

@php
    use App\Http\Controllers\ServiceController;

    use App\Http\Controllers\ControllerController;

    use App\Http\Controllers\EntrepriseController;

    use App\Http\Controllers\ContratController;

    use App\Http\Controllers\PrestationController;

    use App\Http\Controllers\ProspectionController;

    use App\Http\Controllers\FactureController;

    use App\Http\Controllers\InterlocuteurController;

    use App\Http\Controllers\DocController;

    use App\Http\Controllers\CategorieController;

    $contratcontroller = new ContratController();
    $entreprisecontroller = new EntrepriseController();
    $prestationcontroller = new PrestationController();
    $prospectioncontroller = new ProspectionController();
    $facturecontroller = new FactureController();
    $interlocuterController = new InterlocuteurController();
    $documentController = new DocController();
    $categoriecontroller = new CategorieController();
    $servicecontroller = new ServiceController();

@endphp

@section('content')

    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
             
             
            <div class="col-md-3">
                <a href="prospects"><button class="btn btn-default"> <b>RETOUR</b></button></a>
            </div>
             <div class="col-md-3">
                <a href="prospection"><button class="btn btn-warning"> <b>PROSPECTIONS</b></button></a>
             </div>
            <div class="col-md-3">
               <a href="form_add_prospection"><button class="btn btn-primary"> <b>AJOUTER UNE PROSPECTION</b></button></a>
            </div>

             <div class="col-md-3"></div>
        </div>
        <div class="col-md-2"></div>
    </div><br>
    @php
        //dd($id_entreprise);
    @endphp
    @if(isset($id_entreprise))
        
        @php

            $prospections = $prospectioncontroller->GetProspectionByIdEntr($id_entreprise);

            
            
        @endphp

        
        <div class="row">
            @if(session('success'))
                <div class="col-md-12 box-header">
                <p class="bg-success" style="font-size:13px;">{{session('success')}}</p>
                </div>
            @endif

             @if(session('error'))
                <div class="col-md-12 box-header">
                <p class="bg-danger" style="font-size:13px;">{{session('error')}}</p>
                </div>
            @endif

            @if(isset($error))
                <div class="col-md-12 box-header">
                <p class="bg-danger" style="font-size:13px;">{{$error}}</p>
                </div>
            @endif

             @if(isset($success))
                <div class="col-md-12 box-header">
                <p class="bg-success" style="font-size:13px;">{{$success}}</p>
                </div>
            @endif
           
        
             
        </div>
  

        <div class="row">
          
            <div class="col-md-2"></div>
            <!-- left column -->
            <div class="col-md-8">
                <!-- Horizontal Form -->
                <div class="box box-info">
                    @php
                        $c = $prospections->count();
                        
                        if($c == 0)
                        {
                            echo '<h3 class="box-title"><b>Pas de prospections pour cette entreprise!</b></h3>';
                        }
                    @endphp
                      <div class="box-header with-border" style="text-align:center">
                      @php
                        $nom = $entreprisecontroller->GetById($id_entreprise)
                      @endphp
                      @foreach($nom as $nom)
                            <h3 class="box-title"><b>{{$nom->nom_entreprise}}</b></h3>
                            </div>
                            <!-- /.box-header -->
                      @endforeach
                       
                    @foreach($prospections as $prospections)
                      

                        <!-- form start  INFO SUR LA PROPESCTION DANS LA TABLE-->
                        <div class="form-horizontal">
                         
                            <div class="box-body">
                                <div class="form-group">
                                    <label class="col-sm-6 control-label"><b>DATE :</b></label>
                                
                                    <div class="col-sm-6">
                                     <input type="text" class="form-control" disabled value="@php echo date('d/m/Y', strtotime($prospections->date_prospection)) @endphp">
                                    </div>
                                  
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-6 control-label"> <b>ADRESSE DE L'ENTREPRISE :</b></label>
                                  
                                
                                    <div class="col-sm-6">
                                     <input type="text" value="{{$prospections->adresse}}" class="form-control" disabled>
                                    </div>
                                   
                                </div>
                                <div class="form-group">
                                      <label class="col-sm-6 control-label"><b>EN CHARGE DE LA PROSPECTION :</b></label>
                                
                                    <div class="col-sm-6">
                                       <input class="form-control" disabled type="text" value="{{$prospections->nom_prenoms}}">
                                    </div>
                              
                                </div>
                            
                            </div>
               
                        </div>
                        <div class="box-body">
                            <form action="go_contrat_form" method="post" >
                                    @csrf
                                    <input type="text" value={{$id_entreprise}} style="display:none;" name="id_entreprise">
                                    <button type="submit" class="btn btn-success"><i class="fa fa-edit">AJOUTER UN CONTRAT</i></button>
                            </form>
                        </div>
                       
                        <div class="box-header">
                            <h3 class="box-title"><b>Service(s) proposé(s)</b></h3>
                        </div>
                        
                        <div class="form-group ">
                            <div class="box-body">
                                @php
                                    //On va écrire un code pour detecter tous les services offerts
                                    $se = DB::table('prospection_services')
                                    ->join('prospections', 'prospection_services.prospection_id', '=', 'prospections.id')
                                    ->join('services', 'prospection_services.service_id', '=', 'services.id') 
                                    ->where('prospection_id', $prospections->id)    
                                    ->get(['services.libele_service', 'prospection_services.*']);
                                @endphp

                                <div class="form-group no-padding">
                                    <table class="table table-hover box-body">
                                    
                                        <tr>
                                    
                                            <th>Nom</th>
                                            
                                            <th style="width: 40px">Ajouté le :</th>
                                            <th>Supprimer</th>
                                        </tr>
                                        <!--LES FICHIERS ET LES FACTURES-->
                                        
                                        @foreach($se as $se_get)
                                            <tr>
                                                <td>  <span class="text"><b>{{$se_get->libele_service}}</b></span></td>
                                            
                                                <td>
                                                        @php 
                        
                                                        echo "<b>".date('d/m/Y',strtotime($se_get->created_at))."</b> à <b>".date('H:i:s',strtotime($se_get->created_at))."</b>" ;
                                                
                                                    @endphp
                                                </td>
                                                <td>
                                                    <form action="delete_service_many_to_many" method="post" >

                                                        @csrf
                                                        <div class="box-body">
                                                            <div class="form-group col-sm-6">
                                                                <input type="text" value="{{$prospections->id}}" style="display:none;" name="id_prospection">
                                                                <input type="text" value="{{$se_get->id}}" style="display:none;" name="id_service">
                                                                 <input type="text" value="{{$id_entreprise}}" style="display:none;" name="id_entreprise">
                                                                <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                                                            </div>

                                                        </div>
                                                    
                                                    </form>

                                                </td>
                                            </tr>
                                        
                                        @endforeach
                                    
                                    </table>
                                </div>

                            
                                
                                
                                </ul>
                            </div>
                        </div>

                        <div class="box-body">
                            <form action="add_service_in_fiche" method="post" class="col-sm-12">
                                @csrf
                                <input type="text" value="{{$prospections->id}}" style="display:none;" name="id_prospection">
                                <input type="text" value="{{$id_entreprise}}" style="display:none;" name="id_entreprise">
                                <div class="form-group">
                                    <label>Ajouter un service</label>
                                    <select class="form-control input-lg select2" data-placeholder ="--Selctionner un service--" multiple="multiple" name="service_propose[]" required>
                                    
                                        <!--liste des services a choisir -->
                                    
                                        @php
                                            $get = $servicecontroller->GetAll();
                                            $categorie = $categoriecontroller->DisplayAll();
                                        @endphp
                                        @foreach( $categorie as $categorie)
                                            
                                            <optgroup label="{{$categorie->libele_categorie}}">{{$categorie->libele_categorie}}</optgroup>
                                            @php
                                                $get = $servicecontroller->GetByCategorie($categorie->id);
                                                
                                            @endphp
                                            @foreach($get as $service)
                                                <option value={{$service->id}}>{{$service->libele_service}}</option>
                                                
                                            @endforeach
                                        @endforeach
                                    
                                    </select>
                                
                                    
                                </div>
                                <button class="btn btn-primary">Ajouter</button>
                            </form>
                        </div>
                        <hr>

                         <!--LES FICHIERS ET LES FACTURES DANS LA TABLE PROSPECTION-->

                        <div class="box-header with-border">
                            <h3 class="box-title"><b>FACTURE PROFORMA</b></h3>
                        </div>
                           
                        <div class="no-padding">
                            <table class="table table-hover box-body">
                               
                                <tr>
                                    <th>Nom</th>
                                    <th>Supprimer</th>
                                    <th style="width: 40px">Aperçu</th>
                                </tr>
                                <!--LES FICHIERS ET LES FACTURES-->
                                <tr>
                                    <td>  <span class="text">{{$prospections->facture_path}}</span> </td>
                                    <td>
                                        <form action="edit_prospect_form" method="post">
                                            @csrf
                                            <input type="text" value={{$prospections->id}} style="display:none;" name="id_prospection">
                                            <button type="submit" class="btn btn-success"><i class="fa fa-edit">Aller a la page prospections pour modifier</i></button>
                                        </form>
                                    </td>
                                    <td>
                                        
                                        <form action="download_facture_proforma" method="post" enctype="multipart/form-data">

                                            @csrf
                                            <div class="box-body">
                                                <div class="form-group col-sm-6">
                                                    <input type="text" value="{{$prospections->id}}" style="display:none;" name="id_prospection">
                                                    <input type="text" value="{{$id_entreprise}}" style="display:none;" name="id_entreprise">
                                                    <input type="text" class="form-control" name="file" value="{{$prospections->facture_path}}"  style="display:none;">
                                                    <button type="submit" class="btn btn-warning"><i class="fa fa-download"></i></button>
                                                </div>

                                            </div>
                                        
                                        </form>

                                    </td>
                                </tr>
                            
                            </table>
                        </div>

                        <!--LES AUTRES PROFORMA-->
                        <div class="no-padding">
                            <table class="table table-hover box-body">
                                @php
                                    $select = DB::table('docfactures')
                                                ->where('id_prospection', $prospections->id)
                                                ->get();
                                @endphp
                               
                                
                                @foreach($select as $select)
                                    <tr>
                                        <td>  <span class="text">{{$select->libele}}</span> </td>
                                        <td>
                                            <form action="delete_prof_in_fiche" method="post" >

                                                @csrf
                                                <div class="box-body">
                                                    <div class="form-group col-sm-6">
                                                    <input type="text" value="{{$select->id}}" style="display:none;" name="id_doc">
                                                        <input type="text" value="{{$prospections->id}}" style="display:none;" name="id_prospection">
                                                        <input type="text" value="{{$id_entreprise}}" style="display:none;" name="id_entreprise">
                                                        <input type="text" class="form-control" name="file" value="{{$select->path_doc}}"  style="display:none;">
                                                        <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                                                    </div>

                                                </div>
                                            
                                            </form>
                                        </td>
                                        <td>
                                            
                                            <form action="download_facture_proforma" method="post" enctype="multipart/form-data">

                                                @csrf
                                                <div class="box-body">
                                                    <div class="form-group col-sm-6">
                                                        <input type="text" value="{{$prospections->id}}" style="display:none;" name="id_prospection">
                                                        <input type="text" value="{{$id_entreprise}}" style="display:none;" name="id_entreprise">
                                                        <input type="text" class="form-control" name="file" value="{{$select->path_doc}}"  style="display:none;">
                                                        <button type="submit" class="btn btn-warning"><i class="fa fa-download"></i></button>
                                                    </div>

                                                </div>
                                            
                                            </form>

                                        </td>
                                    </tr>
                                @endforeach
                              
                            
                            </table>
                        </div>

                         <!--SI ON VEUT AJOUETR UN AUTRE DOCUMENT de FACTURE PROFORMA -->
                        <div class="box-body">
                            <form action="add_new_doc_proforma" method="post" enctype="multipart/form-data" class="col-sm-12">

                                @csrf
                                <div class="box-body ">
                                    <div class="form-group col-sm-6">
                                        <input type="text" value="{{$prospections->id}}" style="display:none;" name="id_prospection">
                                        <input type="text" value="{{$id_entreprise}}" style="display:none;" name="id_entreprise">
                                        <label class="control-label">Ajouter une facture :</label>
                                        <input type="file" class="form-control" name="new_doc_proforma" required>
                                        <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i></button>
                                    </div>

                                </div>
                                
                            </form>
                        </div>

                        <!--LES CR DE VISITE DANS LA TABLE PROPSECTION-->
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>COMPTE RENDU DE VISITE</b></h3>
                        </div>
                         
                        <div class="form-group no-padding">
                            <table class="table table-hover box-body">
                               
                                <tr>
                            
                                    <th>Nom</th>
                                    <th>Supprimer</th>
                                    <th style="width: 40px">Aperçu</th>
                                </tr>
                               
                                <tr>
                                    <td>  <span class="text">{{$prospections->path_cr}}</span> </td>
                                
                                     <td>
                                        <form action="edit_prospect_form" method="post">
                                            @csrf
                                            <input type="text" value={{$prospections->id}} style="display:none;" name="id_prospection">
                                            <button type="submit" class="btn btn-success"><i class="fa fa-edit">Aller a la page prospections pour modifier</i></button>
                                        </form>
                                    <td>
                                    <td>    
                                        <form action="download_prospect" method="post" enctype="multipart/form-data">

                                            @csrf
                                            <div class="box-body">
                                                <div class="form-group col-sm-6">
                                                    <input type="text" value="{{$prospections->id}}" style="display:none;" name="id_prospection">
                                                    <input type="text" value="{{$id_entreprise}}" style="display:none;" name="id_entreprise">
                                                    <input type="text" class="form-control" name="file" value="{{$prospections->path_cr}}" style="display:none;">
                                                    <button type="submit" class="btn btn-warning"><i class="fa fa-download"></i></button>
                                                </div>

                                            </div>
                                        
                                        </form>

                                    </td>
                                </tr>
                            
                            </table>
                        </div>

                         <!--LES AUTRES CR DE VISITE-->
                        <div class="no-padding">
                            <table class="table table-hover box-body">
                               @php
                                    $select = DB::table('compterendus')
                                                ->where('id_prospection', $prospections->id)
                                                ->get();
                               @endphp
                               
                                <!--LES FICHIERS ET LES FACTURES-->
                                @foreach($select as $select)
                                    <tr>
                                        <td>  <span class="text">{{$select->libele}}</span> </td>
                                        <td>
                                            <form action="delete_cr_in_fiche" method="post" >

                                                @csrf
                                                <div class="box-body">
                                                    <div class="form-group col-sm-6">
                                                    <input type="text" value="{{$select->id}}" style="display:none;" name="id_doc">
                                                        <input type="text" value="{{$prospections->id}}" style="display:none;" name="id_prospection">
                                                        <input type="text" value="{{$id_entreprise}}" style="display:none;" name="id_entreprise">
                                                        <input type="text" class="form-control" name="file" value="{{$select->path_doc}}"  style="display:none;">
                                                        <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                                                    </div>

                                                </div>
                                            
                                            </form>
                                            
                                        </td>
                                        <td>
                                            
                                            <form action="download_facture_proforma" method="post" enctype="multipart/form-data">

                                                @csrf
                                                <div class="box-body">
                                                    <div class="form-group col-sm-6">
                                                        <input type="text" value="{{$prospections->id}}" style="display:none;" name="id_prospection">
                                                        
                                                        <input type="text" class="form-control" name="file" value="{{$select->path_doc}}"  style="display:none;">
                                                        <button type="submit" class="btn btn-warning"><i class="fa fa-download"></i></button>
                                                    </div>

                                                </div>
                                            
                                            </form>

                                        </td>
                                    </tr>
                                @endforeach
                              
                            
                            </table>
                        </div>

                         <!--SI ON VEUT AJOUETR UN AUTRE DOCUMENT -->
                        <div class="box-body">
                            <form action="add_new_doc_cr" method="post" enctype="multipart/form-data" class="col-sm-12">

                                @csrf
                                <div class="box-body ">
                                    <div class="form-group col-sm-6">
                                        <input type="text" value="{{$prospections->id}}" style="display:none;" name="id_prospection">
                                        <input type="text" value="{{$id_entreprise}}" style="display:none;" name="id_entreprise">
                                        <label class="control-label">Ajouter un CR de visite :</label>
                                        <input type="file" class="form-control" name="new_doc_cr" required>
                                        <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i></button>
                                    </div>

                                </div>
                                
                            </form>
                        </div>

                        @php
                            $docs = $documentController->GetDocByProspection($prospections->id);  
                        @endphp

                        <!--AUTRE DOCS-->
                    
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>AUTRE DOCUMENTS (facture supplémentaires & autres)</b></h3>
                        </div>
                        <div class="box-body no-padding">
                            <table class="table table-hover">
                                <tr>
                            
                                    <th>Nom</th>
                                    <th>Supprimer</th>
                                    <th style="width: 40px">Aperçu</th>
                                </tr>
                                @foreach($docs as $docs)
                                    <!--LES FICHIERS ET LES FACTURES-->
                                <tr>
                                    <td>  <span class="text">{{$docs->libele}}</span> </td>
                                    <td>
                                        <form action="delete_doc" method="post" enctype="multipart/form-data">

                                            @csrf
                                            <input type="text" value="{{$prospections->id}}" style="display:none;" name="id_prospection">
                                            <input type="text" value="{{$id_entreprise}}" style="display:none;" name="id_entreprise">
                                            <input type="text" value="{{$docs->id}}" style="display:none;" name="id_doc">
                                            <input type="text" class="form-control" name="file" value="{{$docs->path_doc}}" style="display:none;">
                                            <button type="submit" class="btn btn-sx btn-danger"><i class="fa fa-trash-o"></i></button>
                                        </form>
                                    </td>
                                    <td>
                                        
                                        <form action="download_docs" method="post" enctype="multipart/form-data" class="col-sm-6">

                                            @csrf
                                            <input type="text" value="{{$prospections->id}}" style="display:none;" name="id_prospection">
                                            <input type="text" value="{{$docs->id}}" style="display:none;" name="id_doc">
                                            <input type="text" class="form-control" name="file" value="{{$docs->path_doc}}" style="display:none;">
                                            <button type="submit" class="btn btn-warning"><i class="fa fa-download"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                
                                @endforeach
                            </table>
                        </div>
                        <hr>
                       
                        
                        <!--SI ON VEUT AJOUETR UN AUTRE DOCUMENT -->
                        <div class="box-body">
                            <form action="add_new_doc" method="post" enctype="multipart/form-data" class="col-sm-12">

                                @csrf
                                <div class="box-body ">
                                    <div class="form-group col-sm-6">
                                        <input type="text" value="{{$prospections->id}}" style="display:none;" name="id_prospection">
                                        <input type="text" value="{{$id_entreprise}}" style="display:none;" name="id_entreprise">
                                        <label class="control-label">Ajouter un document :</label>
                                        <input type="file" class="form-control" name="new_doc" required>
                                        <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i></button>
                                    </div>

                                </div>
                                
                            </form>
                        </div>
                        
                    @endforeach
                   
                    
                    <hr>
                    @php
                            
                        $interlocuteurs =  $interlocuterController->InterlocuteurWithIdEntreprise($id_entreprise);
                        
                    @endphp
                    <div class="box-header with-border">
                        <h3 class="box-title"><b>Interlocuteur(s)</b></h3>
                    </div>

                    <table class="table table-hover box-body">
                                    
                        <tr>
                    
                            <th>Nom</th>
                            <th>Téléphone</th>
                             <th>Email</th>
                              <th>Fonction</th>
                          
                            
                        </tr>
                        <!--LES FICHIERS ET LES FACTURES-->
                        
                        @foreach($interlocuteurs as $interlocuteurs)
                            <tr>
                                <td> {{$interlocuteurs->titre}} {{$interlocuteurs->nom}}</td>
                            
                                <td>
                                    {{$interlocuteurs->tel}}
                                </td>
                                <td>{{$interlocuteurs->email}}</td>
                                <td>{{$interlocuteurs->fonction}}</td>
                               
                            </tr>
                        
                        @endforeach
                    
                    </table>
       
                    <hr>
                    <div class="box-body">
                        <!--DEUXIEMEN PARTIE DU FORMULAIRE-->
                        <form action="add_referant_in_fiche" method="post">         
                            @csrf
                            <div class="box-header">
                                <h3 class="box-title"><b>AJOUTER UN INTERLOCUTEUR </b></h3>
                            </div> 

                            <div class="form-group">
                                @php
                                    $nom = $entreprisecontroller->GetById($id_entreprise)
                                @endphp
                              
                                        
                                <select class="form-control input-lg" name="entreprise">
                                    @foreach($nom as $nom)
                                        <option value={{$id_entreprise}}>{{$nom->nom_entreprise}}</option>

                                    @endforeach
                                   
                                </select>
                                
                            </div>        

                            <div class="form-group">
                                <label for="exampleInputFile">Titre :</label>
                                <select class="form-control input-lg" name="titre" id="grise1" >
                                    <option value="M">M</option>
                                    <option value="Mme">Mme</option>
                                    <option value="Mlle">Mlle</option>
                                </select>
                                
                            </div>
                            <div class="form-group">
                                    <label >Nom & Prénom(s)</label>
                                    <input type="text" maxlength="100" required id="grise2" class="form-control  input-lg" name="nom" onkeyup="this.value=this.value.toUpperCase()">
                            </div>

                            <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" id="grise5" maxlength="30" class="form-control input-lg" name="email" >
                            </div>

                            <div class="form-group">
                                    <label>Téléphone (*)</label>
                                    <input type="text" required id="grise3" maxlength="30"   class="form-control input-lg" name="tel" placeholder="(+225)0214578931" >
                                </div>

                            <div class="form-group">
                                    <label>Fonction</label>
                                    <input required type="text" class="form-control input-lg"  id="grise4" maxlength="60" name="fonction" onkeyup="this.value=this.value.toUpperCase()">
                            </div>

                            <button class="btn btn-primary" >Ajouter</button>  
                        </form>
                   </div>
                </div>
               
                <!-- /.box -->
             
        
            </div>
            
            <div class="col-md-2"></div>
        </div>
        <!--/.col (right) -->
    @endif
    
@endsection 
 
 
 
          