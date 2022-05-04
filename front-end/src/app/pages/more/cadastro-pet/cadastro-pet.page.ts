import { NavController } from '@ionic/angular';
import { AlertService } from './../../../components/alert.service';
import { Sighted } from './../../../models/sighted';
import { Pet } from './../../../models/pet';
import { PetsService } from './../../../services/pets.service';
import { Photo } from './../../auth/cadastro-foto/cadastro-foto.page';
import { Camera, CameraResultType } from '@capacitor/camera';
import { FormGroup, FormBuilder, FormControl, Validators } from '@angular/forms';
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-cadastro-pet',
  templateUrl: './cadastro-pet.page.html',
  styleUrls: ['./cadastro-pet.page.scss'],
})
export class CadastroPetPage implements OnInit {
  pet = new Pet()
  sighted = new Sighted()

  cadastro_form: FormGroup
  photos: Photo[] = [];
  foto = false
  foto_form

  constructor(
    private formBuilder: FormBuilder,
    private pets: PetsService,
    private alert: AlertService,
    private nav: NavController
  ) { }

  ngOnInit() {
    this.cadastro_form = this.formBuilder.group({
      name: new FormControl('', Validators.compose([Validators.required])),
      species: new FormControl('', Validators.compose([Validators.required])),
      sex: new FormControl('', Validators.compose([Validators.required])),
      breed: new FormControl('', Validators.compose([Validators.required])),
      size: new FormControl('', Validators.compose([Validators.required])),
      predominant_color: new FormControl('', Validators.compose([Validators.required])),
      secondary_color: new FormControl('', Validators.compose([Validators.nullValidator])),
      physical_details: new FormControl('', Validators.compose([Validators.nullValidator])),
      last_seen: new FormControl('', Validators.compose([Validators.required])),
    })
  }

  get f() { return this.cadastro_form.controls }

  petsStore() {
    let data = []

    this.pet.setName(this.cadastro_form.value['name']);
    this.pet.setSpecies(this.cadastro_form.value['species']);
    this.pet.setSex(this.cadastro_form.value['sex']);
    this.pet.setBreed(this.cadastro_form.value['breed']);
    this.pet.setSize(this.cadastro_form.value['size']);
    this.pet.setPredominant_color(this.cadastro_form.value['predominant_color']);
    this.pet.setSecondary_color(this.cadastro_form.value['secondary_color']);
    this.pet.setPhysical_details(this.cadastro_form.value['physical_details']);
    this.pet.setPhoto(this.foto_form);
    this.sighted.setLastSeen(this.cadastro_form.value['last_seen']);

    data.push(this.pet)
    data[0]['last_seen'] = this.sighted.last_seen;

    try {
      this.pets.petsStore(data[0])
        .then((res) => {
          this.alert.showAlertSuccess(res['success']);
          this.nav.back();
        })
        .catch(err => {
          this.alert.showAlertError(err);
        })
    } catch (err) {
      console.log("erro " + err)
    }
  }

  changeDate(event) {
    var myDate = new Date(event.detail.value);
    let dataFormat = `${myDate.getFullYear() + '-' + (myDate.getMonth() + 1) + '-' + myDate.getDate()}`;

    this.pet.setDate_disappearance(dataFormat);
    console.log(this.pet);
  }

  async takePicture() {
    this.photos = []
    try {
      await Camera.getPhoto({
        quality: 90,
        allowEditing: false,
        promptLabelHeader: 'Câmera',
        promptLabelPhoto: 'Galeria de imagens',
        promptLabelPicture: 'Tirar foto',
        resultType: CameraResultType.Base64
      }).then(image => {
        this.photos.unshift({
          filepath: "soon...",
          webviewPath: "data:image/jpeg;base64, " + image.base64String
        });
        this.foto_form = image.base64String;
      });
      this.foto = true
    } catch (error) {
      console.error(error);
    }
  }
}
