import { LoadingService } from './../../../components/loading.service';
import { User } from './../../../models/user';
import { AuthService } from './../../../services/auth.service';
import { ConfirmacaoPage } from './../../../modals/confirmacao/confirmacao.page';
import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormControl, FormGroup, Validators } from '@angular/forms';
import { ModalController, LoadingController, NavController } from '@ionic/angular';

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
    private nav: NavController,

  ) { }

  ngOnInit() {
    this.login_form = this.formBuilder.group({
      email: new FormControl('', Validators.compose([Validators.required, Validators.email])),
      password: new FormControl('', Validators.compose([Validators.required, Validators.minLength(8)]))
    })
  }

  get f() { return this.login_form.controls }

  async doLogin() {
    this.loading.presentLoading();
    this.user.setUsuarioData(this.login_form.value)
    let usuario = await this.user.getUsuarioData()
    console.log(usuario);

    try {
      this.auth.login(usuario).then(() => {
        this.loading.dismissLoading();
        this.nav.navigateForward('');
      })
        .catch(err => {
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
