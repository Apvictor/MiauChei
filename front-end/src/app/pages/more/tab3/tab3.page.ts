import { UserService } from './../../../services/user.service';
import { LoadingService } from './../../../components/loading.service';
import { Camera, CameraResultType } from '@capacitor/camera';
import { User } from './../../../models/user';
import { Photo } from './../../auth/cadastro-foto/cadastro-foto.page';
import { FormGroup, FormControl, Validators, FormBuilder } from '@angular/forms';
import { Router } from '@angular/router';
import { HttpClient } from '@angular/common/http';
import { AlertController, LoadingController, ToastController } from '@ionic/angular';
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
    private userService: UserService
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

  async profile() {
    this.loading.presentLoading();
    try {
      this.userService.profile().then((user) => {
        this.loading.dismissLoading();
        this.user.setName(user['name']);
        this.user.setEmail(user['email']);
        this.user.setPhone(user['phone']);
        this.user.setPhoto(user['photo']);
      })
        .catch(err => {
        })
    } catch (err) {
      console.log("erro " + err)
    }
  }












  async takePicture() {
    this.photos = []

    try {
      const profilePicture = await Camera.getPhoto({
        quality: 90,
        allowEditing: false,
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
