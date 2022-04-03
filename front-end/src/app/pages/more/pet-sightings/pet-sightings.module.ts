import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { PetSightingsPageRoutingModule } from './pet-sightings-routing.module';

import { PetSightingsPage } from './pet-sightings.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    PetSightingsPageRoutingModule
  ],
  declarations: [PetSightingsPage]
})
export class PetSightingsPageModule {}
