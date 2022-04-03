import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { CadastroSightedPageRoutingModule } from './cadastro-sighted-routing.module';

import { CadastroSightedPage } from './cadastro-sighted.page';

import { NgxMaskModule } from 'ngx-mask';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    ReactiveFormsModule,
    IonicModule,
    CadastroSightedPageRoutingModule,
    NgxMaskModule.forChild()
  ],
  declarations: [CadastroSightedPage]
})
export class CadastroSightedPageModule {}
