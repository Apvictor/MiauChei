import { Pet } from './../../../models/pet';
import { NavController } from '@ionic/angular';
import { AlertService } from './../../../components/alert.service';
import { Sighted } from './../../../models/sighted';
import { ActivatedRoute } from '@angular/router';
import { PetsService } from './../../../services/pets.service';
import { FormGroup, FormBuilder, FormControl, Validators } from '@angular/forms';
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-cadastro-sighted',
  templateUrl: './cadastro-sighted.page.html',
  styleUrls: ['./cadastro-sighted.page.scss'],
})
export class CadastroSightedPage implements OnInit {
  sighted = new Sighted();
  cadastro_form: FormGroup

  constructor(
    private formBuilder: FormBuilder,
    private petsService: PetsService,
    private route: ActivatedRoute,
    private alert: AlertService,
    private nav: NavController
  ) { }

  ngOnInit() {
    this.route.params.subscribe(parametros => {
      if (parametros['id']) {
        this.sighted.pet_id = parametros['id'];
      }
    })

    this.cadastro_form = this.formBuilder.group({
      data_sighted: new FormControl('', Validators.compose([Validators.required])),
      last_seen: new FormControl('', Validators.compose([Validators.required])),
      user_pet: new FormControl('', Validators.compose([Validators.required])),
    })
  }

  get f() { return this.cadastro_form.controls }

  petsSightedStore() {
    this.sighted.setDataSighted(this.cadastro_form.value['data_sighted']);
    this.sighted.setLastSeen(this.cadastro_form.value['last_seen']);
    this.sighted.setUserPet(this.cadastro_form.value['user_pet'] == 'true' ? true : false);

    try {
      this.petsService.petsSightedStore(this.sighted)
        .then(() => {
          this.nav.back();
        })
        .catch(err => {
          this.alert.showAlertError(err);
        })
    } catch (err) {
      console.log("erro " + err)
    }
  }

}
