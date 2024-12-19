@extends('layouts/base')

@php
    
    use App\Http\Controllers\EntrepriseController;

    use App\Http\Controllers\StatutEntrepriseController;

    use App\Http\Controllers\PaysController;

    $entreprisecontroller = new EntrepriseController();

    $statutentreprisecontroller = new StatutEntrepriseController();

    $payscontroller = new PaysController();

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

            <div class="col-md-3">
       
            </div>
        
            <div class="col-md-6">

               <!-- general form elements -->
                <div class="box box-aeneas">
                <div class="box-header with-border">
                    <h3 class="box-title">AJOUTER UN PROSPECT</h3><br>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                    
                </div>
            
                <!-- form start -->
                <form role="form" method="post" action="add_prospect">
                    @csrf
                    <div class="box-body">
                        
                        <div class="form-group">
                            <label>Désignation :</label>
                            <input type="text" class="form-control input-lg" name="nom_entreprise" onkeyup='this.value=this.value.toUpperCase()'  required />
                        </div> 
                    
                        <div class="form-group">
                        <label >Adresse:</label>
                        <input type="text" maxlength="18" class="form-control  input-lg" name="adresse" placeholder="COCODY" onkeyup="this.value=this.value.toUpperCase()">
                        </div>
                        <div class="form-group">
                        <label >Téléphone (fixe/mobile):</label>
                        <input type="text"  maxlength="18" class="form-control  input-lg" name="tel" placeholder="+225 27 47 54 45 68">
                        </div>
                        <div class="form-group">
                        <label >Chiffre d'affaire (FCFA): </label>
                        <input type="text" id="ca" maxlength="18" class="form-control  input-lg" name="chiffre" placeholder="1000000">
                        </div>

                        <div class="form-group">
                        <label >Nombre d'employés:</label>
                        <input type="text" id="ne" maxlength="18" class="form-control  input-lg" name="nb_emp" placeholder="5">
                        </div>

                        <div class="form-group">
                            <label >Objet social/Activité:</label>
                            <input type="text"  maxlength="100" class="form-control  input-lg" 
                            name="activite" onkeyup='this.value=this.value.toUpperCase()'>
                        </div>

                        <div class="form-group">
                            <label>Email:</label>
                            <input type="email"  maxlength="30" class="form-control  input-lg" 
                            name="email">
                        </div>

                        <div class="form-group">
                            <label>Pays :</label>
                            <select class="form-control input-lg" name="pays">
                                @php
                                    $pays = $payscontroller->DisplayAll();
                                @endphp
                                @foreach($pays as $pays)
                                    <option value={{$pays->id}}>{{$pays->nom_pays}}</option>
                                    
                                @endforeach
                                
                            </select>
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

            <div class="col-md-3">
            </div>
      </div>
      <!-- /.row -->
   
@endsection