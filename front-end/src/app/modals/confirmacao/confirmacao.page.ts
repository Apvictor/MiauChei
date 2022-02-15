import { ModalController, NavController } from '@ionic/angular';
import { Component, Input, OnInit } from '@angular/core';

@Component({
  selector: 'app-confirmacao',
  templateUrl: './confirmacao.page.html',
  styleUrls: ['./confirmacao.page.scss'],
})
export class ConfirmacaoPage implements OnInit {

  @Input() tipo: string;

  text
  title
  textBtn

  constructor(
    private modal: ModalController,
    private nav: NavController,
  ) { }

  ngOnInit() {
    if (this.tipo == "login") {
      this.title = "Login efetuado com sucesso!"
    } else if (this.tipo == "cadastro") {
      this.title = "Cadastro efetuado com sucesso!"
    }
  }

  closeModal() {
    this.modal.dismiss();
    if (this.tipo == "cadastro") {
      this.nav.navigateForward('login');
    }
  }

}
