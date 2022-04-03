import { NgxMaskModule } from 'ngx-mask';
import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { CadastroPetPageRoutingModule } from './cadastro-pet-routing.module';

import { CadastroPetPage } from './cadastro-pet.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    ReactiveFormsModule,
    IonicModule,
    CadastroPetPageRoutingModule,
    NgxMaskModule.forChild()
  ],
  declarations: [CadastroPetPage]
})
export class CadastroPetPageModule {}
