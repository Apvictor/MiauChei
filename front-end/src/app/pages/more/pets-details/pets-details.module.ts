import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { PetsDetailsPageRoutingModule } from './pets-details-routing.module';

import { PetsDetailsPage } from './pets-details.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    PetsDetailsPageRoutingModule
  ],
  declarations: [PetsDetailsPage]
})
export class PetsDetailsPageModule {}
