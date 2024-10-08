@extends('layouts/base')

@php
    use App\Http\Controllers\ServiceController;

    use App\Http\Controllers\ControllerController;

    use App\Http\Controllers\EntrepriseController;

    use App\Http\Controllers\ContratController;

    $contratcontroller = new ContratController();

    $my_own =  $contratcontroller->MyOwnContrat(auth()->user()->id);

    $all = $contratcontroller->RetriveAll();
@endphp

@section('content')
     <div class="row">
         @if(session('success'))
            <div class="col-md-12 box-header">
              <p class="bg-success" style="font-size:13px;">{{session('success')}}</p>
            </div>
          @endif
            @if(session('error'))
            <div class="col-md-12 box-header">
              <p class="bg-warning" style="font-size:13px;">{{session('error')}}</p>
            </div>
          @endif

          <div class="col-md-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Contrats</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                  <table id="example1" class="table table-bordered table-striped table-hover">
                  <thead>
                  <tr>
                    <th>Titre de contrat</th>
                    <th>Entreprise</th>
                    <th>Type de contrat</th>
                    <th>Service</th>
                   <th>Début du contrat</th>
                   <th>Fin du contrat</th>
                    <th>Montant</th>	
                   
                    @if(auth()->user()->id_role == 3)
                    @else
                        <th>Action</th>
                    @endif
                  </tr>
                  </thead>
                  <tbody>
                      @foreach($all as $all)
                        <tr>
                          <td>{{$all->titre_contrat}}</td>
                          <td>{{$all->nom_entreprise}}</td>
                          <td>{{$all->libele}}</td>
                          <td>{{$all->libele_service}}</td>
                          <td>@php echo date('d/m/Y',strtotime($all->debut_contrat)) @endphp</td>
                           <td>@php echo date('d/m/Y',strtotime($all->fin_contrat)) @endphp</td>
                          <td>
                            @php
                              echo  number_format($all->montant, 2, ".", " ")." XOF";
                            @endphp
                           
                          </td>  
                          @if(auth()->user()->id_role == 3)
                          @else
                            <td>
                              <form action="edit_contrat_form" method="post">
                                  @csrf
                                  <input type="text" value={{$all->id}} style="display:none;" name="id_contrat">
                                  <button type="submit" class="btn btn-success"><i class="fa fa-edit"></i></button>
                              </form>

                              <form action="upload" method="post" enctype="multipart/form-data">
                                @csrf
                                <label>Fichier scanné</label>
                                 <input type="text" value={{$all->id}} style="display:none;" name="id_contrat">
                                <input type="file" class="form-control" name="file">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i></button>
                              </form>

                              <form action="download" method="post" enctype="multipart/form-data">
                                @csrf
                                <label>Télécharger</label>
                                 <input type="text" value={{$all->id}} style="display:none;" name="id_contrat">
                                <input type="text" class="form-control" name="file" value="{{$all->path}}" style="display:none;">
                                <button type="submit" class="btn btn-warning"><i class="fa fa-download"></i></button>
                              </form>
                            </td>
                          @endif  
                         
                        </tr>
                      @endforeach
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>Titre de contrat</th>
                    <th>Entreprise</th>
                    <th>Type de contrat</th>
                    <th>Service</th>
                    <th>Début du contrat</th>
                    <th>Fin du contrat</th>
                    <th>Montant</th>	
                   
                    @if(auth()->user()->id_role == 3)
                    @else
                        <th>Action</th>
                    @endif
                  </tr>
                  </tfoot>
                  </table>
                </div>
                <!-- /.box-body -->
              </div>
              <!-- /.box -->
            </div>
            <!-- /.col --> 
      </div>
          <!-- /.row -->
		<div class="row">
          <div class="col-md-3">
          </div>
      
          <!-- left column -->
          <div class="col-md-6">
            <!-- general form elements -->
            <div class="box box-aeneas">
              <div class="box-header with-border">
                <h3 class="box-title">ENREGISTRER UN CONTRAT</h3><br>(*) champ obligatoire
              </div>
            
              <!-- form start -->
              <form role="form" method="post" action="add_contrat">
                @csrf
                <div class="box-body">
                  <div class="form-group">
                    <label>Entreprise:</label>
                    <select class="form-control input-lg" name="entreprise">
                      @php
                            $get = (new EntrepriseController())->GetAll();
                        @endphp
                        <option value="0">--Choisir une entreprise--</option>
                        @foreach($get as $entreprise)
                            <option value={{$entreprise->id}}>{{$entreprise->nom_entreprise}}</option>
                            
                        @endforeach
                        <option value="autre">Autre<option>
                    </select>
                      
                  </div>    

                  <div class="form-group">
                    <label>Titre</label>
                    <input type="text"  class="form-control input-lg" name="titre" placeholder="Ex: Contrat de sureté BICICI"/>
                  </div>
            
                  <div class="form-group">
                    <label >Montant (XOF)</label>
                    <input type="text" class="form-control  input-lg" required name="montant">
                  </div>
            
                  <div class="form-group">
                    <label>Debut du contrat</label>
                    <input type="date" class="form-control  input-lg" required name="date_debut">
                  </div>

                  <div class="form-group">
                    <label>Date de solde</label>
                    <input type="date" class="form-control  input-lg" required name="date_solde">
                  </div>

                <div class="form-group">
                  <label>Durée du contrat</label>
                  <!--FAIRE DES CALCULS POUR DETERMINER LA FIN DU CONTRAT-->
                    <div class="row">
                      <div class="col-md-3">
                        <input type="number" class="form-control" placeholder="jours" min="1" max="31" name="jour" required>
                      </div>
                      <div class="col-md-4">
                        <input type="number" class="form-control" placeholder="mois" min="1" max="12" name="mois">
                      </div>
                      <div class="col-md-5">
                        <input type="number" class="form-control" placeholder="année" min="1" max="10" name="annee">
                      </div>
                    </div>
                </div>
                
                </div>
                <!-- /.box-body -->

                <div class="box-footer">
                  <button type="submit" class="btn btn-primary">VALIDER</button>
                </div>
              </form>
            </div>
            <!-- /.box -->
          </div>
          <!--/.col (left) -->
          <!-- right column -->
          <div class="col-md-3">
		  		</div>
    </div>
    <!--/.col (right) -->
@endsection