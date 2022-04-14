import { AlertService } from './../../../components/alert.service';
import { ConfirmacaoPage } from './../../../modals/confirmacao/confirmacao.page';
import { ActivatedRoute } from '@angular/router';
import { AuthService } from './../../../services/auth.service';
import { NavController, ModalController } from '@ionic/angular';
import { User } from './../../../models/user';
import { FormBuilder, FormGroup, FormControl, Validators } from '@angular/forms';
import { Component, OnInit } from '@angular/core';
import { Camera, CameraResultType } from '@capacitor/camera';

export interface Photo {
  filepath: string;
  webviewPath: string;
}

@Component({
  selector: 'app-cadastro-foto',
  templateUrl: './cadastro-foto.page.html',
  styleUrls: ['./cadastro-foto.page.scss'],
})
export class CadastroFotoPage implements OnInit {
  cadastro_form: FormGroup
  photos: Photo[] = [];
  foto = false
  foto_form
  user = new User()

  constructor(
    private formBuilder: FormBuilder,
    private auth: AuthService,
    private route: ActivatedRoute,
    private nav: NavController,
    private modal: ModalController,
    private alert: AlertService
  ) { }

  ngOnInit() {
    this.cadastro_form = this.formBuilder.group({
      phone: new FormControl('', Validators.compose([Validators.required])),
    })

    this.route.queryParams.subscribe(params => {
      this.user.setName(params['name']);
      this.user.setEmail(params['email']);
      this.user.setPassword(params['password']);
    });
  }

  get f() { return this.cadastro_form.controls }

  async doCadastro() {
    this.user.setPhoto(this.foto_form);
    this.user.setPhone(this.cadastro_form.value['phone']);
    this.showModal();

    try {
      this.auth.cadastro(this.user)
        .then((res) => {
          this.alert.showAlertSuccess(res['success']);
          this.nav.navigateForward('login');
        }).catch((err) => {
          this.alert.showAlertError(err);
        })
    } catch (err) {
      console.log("erro " + err)
    }
  }

  async showModal() {
    const modal = await this.modal.create({
      component: ConfirmacaoPage,
      cssClass: "modalConfirmacao",
      swipeToClose: true,
      componentProps: {
        "tipo": "cadastro",
      }
    })

    return await modal.present();
  }


  async takePicture() {
    this.photos = []

    try {
      const profilePicture = await Camera.getPhoto({
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
