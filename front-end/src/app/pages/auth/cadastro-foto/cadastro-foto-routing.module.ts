import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { CadastroFotoPage } from './cadastro-foto.page';

const routes: Routes = [
  {
    path: '',
    component: CadastroFotoPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class CadastroFotoPageRoutingModule {}
