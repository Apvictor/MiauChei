import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { PetsSightedPageRoutingModule } from './pets-sighted-routing.module';

import { PetsSightedPage } from './pets-sighted.page';

import { Ng2SearchPipeModule } from 'ng2-search-filter';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    Ng2SearchPipeModule,
    PetsSightedPageRoutingModule
  ],
  declarations: [PetsSightedPage]
})
export class PetsSightedPageModule {}
