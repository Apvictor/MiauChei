import { NgxMaskModule } from 'ngx-mask';
import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { CadastroFotoPageRoutingModule } from './cadastro-foto-routing.module';

import { CadastroFotoPage } from './cadastro-foto.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    ReactiveFormsModule,
    IonicModule,
    CadastroFotoPageRoutingModule,
    NgxMaskModule.forChild()
  ],
  declarations: [CadastroFotoPage]
})
export class CadastroFotoPageModule { }
