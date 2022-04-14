import { AlertService } from './../../../components/alert.service';
import { LoadingService } from './../../../components/loading.service';
import { User } from './../../../models/user';
import { AuthService } from './../../../services/auth.service';
import { ConfirmacaoPage } from './../../../modals/confirmacao/confirmacao.page';
import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormControl, FormGroup, Validators } from '@angular/forms';
import { ModalController, NavController, AlertController } from '@ionic/angular';

@Component({
  selector: 'app-login',
  templateUrl: './login.page.html',
  styleUrls: ['./login.page.scss'],
})
export class LoginPage implements OnInit {

  login_form: FormGroup
  user = new User();

  constructor(
    private formBuilder: FormBuilder,
    private modal: ModalController,
    private auth: AuthService,
    private loading: LoadingService,
    private alert: AlertService
  ) { }

  ngOnInit() {
    this.login_form = this.formBuilder.group({
      email: new FormControl('', Validators.compose([Validators.required, Validators.email])),
      password: new FormControl('', Validators.compose([Validators.required, Validators.minLength(8)]))
    })
  }

  get f() { return this.login_form.controls }

  doLogin() {
    this.loading.presentLoading();
    this.user.setEmail(this.login_form.value['email'])
    this.user.setPassword(this.login_form.value['password'])
    this.user.setDevice_name('Android')

    try {
      this.auth.login(this.user)
        .then((res) => {
          this.alert.showAlertSuccess(res['success']);
        })
        .catch(err => {
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
        "tipo": "login",
      }
    })

    return await modal.present();
  }

}
