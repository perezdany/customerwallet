@extends('layouts/base')

@php
    
    use App\Http\Controllers\EntrepriseController;

     use App\Http\Controllers\StatutEntrepriseController;

    $entreprisecontroller = new EntrepriseController();

    $statutentreprisecontroller = new StatutEntrepriseController();

    $all = $entreprisecontroller->GetAll();

    $statut = $statutentreprisecontroller->GetAll();
    
@endphp

@section('content')
      <div class="row">
          @if(session('success'))
            <div class="col-md-12 box-header">
              <p class="bg-success" style="font-size:13px;">{{session('success')}}</p>
            </div>
          @endif
        
            <div class="col-md-6">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Tableaux des Prospects et des clients</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                  <table id="example1" class="table table-bordered table-striped table-hover">
                  <thead>
                  <tr>
                    <th>Nom</th>
                    <th>Statut</th>
                    <th>Adresse</th>
                    <th>Client depuis le:</th>
                    <th>Interlocuteurs: </th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                      @foreach($all as $all)
                        <tr>
                          <td>{{$all->nom_entreprise}}</td>
                          
                          <td>{{$all->libele_statut}}</td>
                          <td>{{$all->adresse}}</td>
                          <td>
                            @php 
                                if($all->client_depuis != NULL)
                                {
                                    echo date('d/m/Y',strtotime($all->client_depuis)) ;
                                }
                                
                            @endphp
                          </td>
                          <td>
                            
                            <form action="display_by_id_entreprise" method="post">
                                @csrf
                                <input type="text" value={{$all->id}} style="display:none;" name="id_entreprise">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-eye"></i>AFFICHER</button>
                            </form>
                          </td>
                          <td>
                            <form action="edit_entreprise_form" method="post">
                                @csrf
                                <input type="text" value={{$all->id}} style="display:none;" name="id_entreprise">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-edit"></i></button>
                            </form>
                             <form action="delete_entreprise" method="post">
                                @csrf
                                <input type="text" value={{$all->id}} style="display:none;" name="id_entreprise">
                                <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                            </form>
                          </td>
                        </tr>
                      @endforeach
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>Nom</th>
                    <th>Statut</th>
                    <th>Adresse</th>
                    <th>Client depuis le:</th>
                    <th>Interlocuteurs: </th>
                    <th>Action</th>
                  </tr>
                  </tfoot>
                  </table>
                </div>
                <!-- /.box-body -->
              </div>
              <!-- /.box -->
            </div>
            <!-- /.col -->
            <div class="col-md-6">

                  <!-- Afficher les interlocuteurs de l'entreprise sélectionnée -->
              @if(isset($interloc))
                  
                <div class="box">
                    <div class="box-header with-border">
                    <h3 class="box-title">INTERLOCUTEURS</h3><br>

                      <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                </div>
                
                      <!-- /.box-header -->
                <div class="box-body">
                    <table id="example1" class="table table-bordered table-striped table-hover">
                        <thead>
                        <tr>
                            <th>Nom & Prénom(s)</th>
                            <th>Téléphone</th>
                            <th>Email</th>
                            <th>Fonction</th>
              
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($interloc as $interloc)
                                <tr>
                                    
                                    <td>{{$interloc->titre}} {{$interloc->nom}}</td>
                                    <td>{{$interloc->tel}}</td>
                                    <td>{{$interloc->email}}</td>
                                    <td>{{$interloc->fonction}}</td>
                                    
                                    <td>
                                        <form action="edit_interlocuteur_form" method="post">
                                            @csrf
                                            <input type="text" value={{$interloc->id}} style="display:none;" name="id_interlocuteur">
                                            <button type="submit" class="btn btn-primary"><i class ="fa fa-edit"></i></button>
                                        </form>
                                        
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>Nom & Prénom(s)</th>
                            <th>Téléphone</th>
                            <th>Email</th>
                            <th>Fonction</th>
                            <th>Action</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
                <!-- /.box-body -->
                  
              @endif

              <!-- general form elements -->
              @if(isset($id_entreprise))
                  @php
                      $edit =  $entreprisecontroller->GetById($id_entreprise);
                  @endphp
                  @foreach($edit as $edit)
                      <div class="box box-aeneas">
                          <div class="box-header with-border">
                            <h3 class="box-title">MODIFIER UNE ENTREPRISE/PARTICULIER</h3><br>

                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                            </div>
                          </div>
                      
                          <!-- form start -->
                          <form role="form" method="post" action="edit_entreprise">
                            <div class="box-body">
                              @csrf
                              <input type="text" name="id_entreprise" value="{{$edit->id}}" style="display:none;">
                              <div class="box-body">
                              
                                  <div class="form-group">
                                      <label>Nom :</label>
                                      <input type="text" class="form-control input-lg" value="{{$edit->nom_entreprise}}" name="nom" onkeyup='this.value=this.value.toUpperCase()'  reuqired />
                                  </div> 
                                  <div class="form-group">
                                      <label>Statut:</label>
                                      @php

                                          $statut = $statutentreprisecontroller->GetAll();
              
                                      @endphp
                                      <select class="form-control input-lg" name="statut" reuqired>
                                          
                                          <option value={{$edit->id_statutentreprise}}>{{$edit->libele_statut}}</option>
                                          @foreach($statut as $statut)
                                              <option value={{$statut->id}}>{{$statut->libele_statut}}</option>
                                              
                                          @endforeach
                                          
                                      </select>
                                  </div>  

                                  <div class="form-group">
                                      <label>Adresse :</label>
                                      <input type="text" class="form-control input-lg" value="{{$edit->adresse}}"  onkeyup='this.value=this.value.toUpperCase()' name="addresse" />
                                  </div>

                                  <div class="form-group">
                                      <label>Client Depuis le :</label>
                                      <input type="date" required class="form-control input-lg" value="{{$edit->client_depuis}}" name="depuis" />
                                  </div>


                                  <div class="box-footer">
                                      <button type="submit" class="btn btn-primary">VALIDER</button>
                                  </div>
                              </div>
                            </div>  <!-- /.box-body -->
                            
                          </form>
                      </div>
                  @endforeach
                  
              @endif

            </div>
      </div>
      <!-- /.row -->
      
    <div class="row"></div>
		<div class="row">
      
        <div class="col-md-6">
          <!-- general form elements -->
            <div class="box box-aeneas">
              <div class="box-header with-border">
                <h3 class="box-title">AJOUTER UNE ENTREPRISE/PARTICULIER</h3><br>
                  <div class="box-tools pull-right">
                      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                      </button>
                      <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                
              </div>
           
              <!-- form start -->
              <form role="form" method="post" action="add_entreprise">
                @csrf
                <div class="box-body">
                    
                    <div class="form-group">
                        <label>Nom :</label>
                        <input type="text" class="form-control input-lg" name="nom" onkeyup='this.value=this.value.toUpperCase()'  reuqired />
                    </div> 
                    <div class="form-group">
                        <label>Statut:</label>

                          <select class="form-control input-lg" name="statut">
                            @php
                                $statut = $statutentreprisecontroller->GetAll();
                            @endphp
                            @foreach($statut as $statut)
                                <option value={{$statut->id}}>{{$statut->libele_statut}}</option>
                                
                            @endforeach
                            
                        </select>
                    </div>  

                    <div class="form-group">
                        <label>Client Depuis le :</label>
                        <input type="date" class="form-control input-lg" name="depuis" value={{$all->client_depuis}}/>
                    </div>

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary">VALIDER</button>
                    </div>
                </div>
                <!-- /.box-body -->
              </form>
              
             
            </div>
            <!-- /.box -->
        </div>
        <!--/.col (left) -->
        

        <!-- right column -->
        <div class="col-md-6">
        </div>
          <!-- /.box -->
		  
    </div>
    <!--/.col (right) -->

@endsection