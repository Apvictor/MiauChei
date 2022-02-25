import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { PetsLostPageRoutingModule } from './pets-lost-routing.module';

import { PetsLostPage } from './pets-lost.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    PetsLostPageRoutingModule
  ],
  declarations: [PetsLostPage]
})
export class PetsLostPageModule {}
