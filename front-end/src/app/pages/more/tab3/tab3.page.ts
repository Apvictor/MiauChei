import { ActionSheetService } from './../../../components/action-sheet.service';
import { AlertService } from './../../../components/alert.service';
import { UserService } from './../../../services/user.service';
import { LoadingService } from './../../../components/loading.service';
import { Camera, CameraResultType } from '@capacitor/camera';
import { User } from './../../../models/user';
import { Photo } from './../../auth/cadastro-foto/cadastro-foto.page';
import { FormGroup, FormControl, Validators, FormBuilder } from '@angular/forms';
import { NavController } from '@ionic/angular';
import { AuthService } from './../../../services/auth.service';
import { Component } from '@angular/core';

@Component({
  selector: 'app-tab3',
  templateUrl: 'tab3.page.html',
  styleUrls: ['tab3.page.scss']
})
export class Tab3Page {
  profile_form: FormGroup

  photos: Photo[] = [];
  foto = false
  foto_form
  user = new User()

  constructor(
    private loading: LoadingService,
    private formBuilder: FormBuilder,
    private userService: UserService,
    private alert: AlertService,
    private auth: AuthService,
    private nav: NavController,
  ) { }

  ngOnInit() {
    this.profile();
    this.profile_form = this.formBuilder.group({
      name: new FormControl('', Validators.compose([Validators.required])),
      email: new FormControl('', Validators.compose([Validators.required, Validators.email])),
      phone: new FormControl('', Validators.compose([Validators.required])),
    })
  }

  get f() { return this.profile_form.controls }

  profile() {
    try {
      this.userService.profile()
        .then((user) => {
          // this.loading.presentLoading();
          this.user.name = user['name'];
          this.user.email = user['email'];
          this.user.phone = user['phone'];
          this.user.photo = user['photo'];
        })
    } catch (err) {
      console.log("erro " + err)
    }
  }

  postProfile() {
    if (this.profile_form.value['name'] != '') {
      this.user.setName(this.profile_form.value['name'])
    } else {
      this.user.name = this.user.getName();
    }

    if (this.profile_form.value['email'] != '') {
      this.user.setEmail(this.profile_form.value['email']);
    } else {
      this.user.email = this.user.getEmail();
    }

    if (this.profile_form.value['phone'] != '') {
      this.user.setPhone(this.profile_form.value['phone']);
    } else {
      this.user.phone = this.user.getPhone();
    }

    if (this.foto == true) {
      this.user.setPhoto(this.foto_form);
    } else {
      this.user.photo = this.user.getPhoto();
    }

    try {
      this.userService.postProfile(this.user)
        .then((res) => {
          this.loading.presentLoading();
          this.alert.showAlertSuccess(res['success']);
        }).catch(err => {
          this.alert.showAlertError(err);
        })
    } catch (err) {
      console.log("erro " + err)
    }
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

  logout() {
    this.alert.alertLogout().then((res) => {
      if (res == true) {
        try {
          this.auth.logout(this.user)
            .then((res) => {
              this.loading.presentLoading();
              this.alert.showAlertSuccess(res['success']);
              this.nav.navigateForward('/login');
            }).catch((err) => {
              this.alert.showAlertError(err);
            })
        } catch (err) {
          console.log("erro " + err);
        }
      }
    });
  }

}
