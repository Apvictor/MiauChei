import { FormGroup, FormBuilder, FormControl, Validators } from '@angular/forms';
import { Component, OnInit } from '@angular/core';
import { NavigationExtras, Router } from '@angular/router';

@Component({
  selector: 'app-cadastro',
  templateUrl: './cadastro.page.html',
  styleUrls: ['./cadastro.page.scss'],
})
export class CadastroPage implements OnInit {
  cadastro_form: FormGroup

  constructor(
    private formBuilder: FormBuilder,
    private router: Router
  ) { }

  ngOnInit() {
    this.cadastro_form = this.formBuilder.group({
      name: new FormControl('', Validators.compose([Validators.required])),
      email: new FormControl('', Validators.compose([Validators.required, Validators.email])),
      password: new FormControl('', Validators.compose([Validators.required, Validators.minLength(8)]))
    })
  }

  get f() { return this.cadastro_form.controls }

  goStep() {
    let navigationExtras: NavigationExtras = {
      queryParams: {
        name: this.cadastro_form.value['name'],
        email: this.cadastro_form.value['email'],
        password: this.cadastro_form.value['password'],
      }
    };
    this.router.navigate(['cadastro-foto'], navigationExtras);
  }
}
