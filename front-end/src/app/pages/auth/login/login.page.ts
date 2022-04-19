import { AlertService } from './../../../components/alert.service';
import { User } from './../../../models/user';
import { AuthService } from './../../../services/auth.service';
import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormControl, FormGroup, Validators } from '@angular/forms';
import { NavController } from '@ionic/angular';

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
    private auth: AuthService,
    private alert: AlertService,
    private nav: NavController
  ) { }

  ngOnInit() {
    this.login_form = this.formBuilder.group({
      email: new FormControl('', Validators.compose([Validators.required, Validators.email])),
      password: new FormControl('', Validators.compose([Validators.required, Validators.minLength(8)]))
    })
  }

  get f() { return this.login_form.controls }

  doLogin() {
    this.user.setEmail(this.login_form.value['email'])
    this.user.setPassword(this.login_form.value['password'])
    this.user.setDevice_name('Android')

    try {
      this.auth.login(this.user)
        .then((res) => {
          this.alert.showAlertSuccess(res['success']);
          this.nav.navigateForward("");
        })
        .catch(err => {
          this.alert.showAlertError(err);
        })
    } catch (err) {
      console.log("erro " + err)
    }
  }
}
