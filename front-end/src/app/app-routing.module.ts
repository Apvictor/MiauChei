import { NgModule } from '@angular/core';
import { PreloadAllModules, RouterModule, Routes } from '@angular/router';

const routes: Routes = [
  {
    path: '',
    loadChildren: () => import('./pages/more/tabs/tabs.module').then(m => m.TabsPageModule)
  },
  {
    path: 'login',
    loadChildren: () => import('./pages/auth/login/login.module').then( m => m.LoginPageModule)
  },
  {
    path: 'confirmacao',
    loadChildren: () => import('./modals/confirmacao/confirmacao.module').then( m => m.ConfirmacaoPageModule)
  },
  {
    path: 'cadastro',
    loadChildren: () => import('./pages/auth/cadastro/cadastro.module').then( m => m.CadastroPageModule)
  },
  {
    path: 'cadastro-foto',
    loadChildren: () => import('./pages/auth/cadastro-foto/cadastro-foto.module').then( m => m.CadastroFotoPageModule)
  }
];
@NgModule({
  imports: [
    RouterModule.forRoot(routes, { preloadingStrategy: PreloadAllModules })
  ],
  exports: [RouterModule]
})
export class AppRoutingModule {}
