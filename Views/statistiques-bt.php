<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="shortcut icon" href="Public/img/icons/safari-icone1.jpeg" />
  <title>Statistiques Billetterie • Safari</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="Public/css/styles.css" />
  <script src="https://unpkg.com/feather-icons"></script>
</head>
<body>
  <div class="app">
    <?php require_once 'includes/menu_BT.php';  ?>

    <!-- Main content -->
    <main class="main">
      <!-- Header -->
      <header class="header">
        <div>
          <h1>Statistiques Billetterie</h1>
          <p>Analyse des performances et indicateurs clés</p>
        </div>
        <div class="header__actions">
          <select class="form-control" style="width: 200px;" id="periodSelector">
            <option value="today">Aujourd'hui</option>
            <option value="week">Cette semaine</option>
            <option value="month" selected>Ce mois</option>
            <option value="year">Cette année</option>
            <option value="custom">Période personnalisée</option>
          </select>
        </div>
      </header>

      <!-- Message en cours de développement -->
      <div style="background: #fef3c7; border: 1px solid #fbbf24; padding: 16px; border-radius: 8px; margin-bottom: 20px;">
        <p style="margin: 0; color: #92400e; font-weight: 600; display: flex; align-items: center; gap: 8px;">
          <i data-feather="alert-triangle" style="width: 20px; height: 20px;"></i>
          Fonctionnalité en cours de développement
        </p>
      </div>

      <!-- KPIs Principaux -->
      <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 24px;">
        <div class="card" style="padding: 20px;">
          <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
            <div style="width: 48px; height: 48px; border-radius: 12px; background: #dbeafe; display: grid; place-items: center;">
              <i data-feather="tag" style="width: 24px; height: 24px; color: #1B4B7F;"></i>
            </div>
            <div style="flex: 1;">
              <div style="font-size: 13px; color: #6b7280;">Billets vendus</div>
              <div style="font-size: 28px; font-weight: 800; color: #1B4B7F;">8,547</div>
            </div>
          </div>
          <div style="font-size: 12px; color: #10b981;">+12% vs mois dernier</div>
        </div>

        <div class="card" style="padding: 20px;">
          <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
            <div style="width: 48px; height: 48px; border-radius: 12px; background: #dcfce7; display: grid; place-items: center;">
              <i data-feather="dollar-sign" style="width: 24px; height: 24px; color: #10b981;"></i>
            </div>
            <div style="flex: 1;">
              <div style="font-size: 13px; color: #6b7280;">Revenus totaux</div>
              <div style="font-size: 28px; font-weight: 800; color: #10b981;">42.7M CDF</div>
            </div>
          </div>
          <div style="font-size: 12px; color: #10b981;">+8% vs mois dernier</div>
        </div>

        <div class="card" style="padding: 20px;">
          <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
            <div style="width: 48px; height: 48px; border-radius: 12px; background: #fef3c7; display: grid; place-items: center;">
              <i data-feather="trending-up" style="width: 24px; height: 24px; color: #f59e0b;"></i>
            </div>
            <div style="flex: 1;">
              <div style="font-size: 13px; color: #6b7280;">Ticket moyen</div>
              <div style="font-size: 28px; font-weight: 800; color: #f59e0b;">5,000 CDF</div>
            </div>
          </div>
          <div style="font-size: 12px; color: #ef4444;">-3% vs mois dernier</div>
        </div>

        <div class="card" style="padding: 20px;">
          <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
            <div style="width: 48px; height: 48px; border-radius: 12px; background: #fee2e2; display: grid; place-items: center;">
              <i data-feather="percent" style="width: 24px; height: 24px; color: #ef4444;"></i>
            </div>
            <div style="flex: 1;">
              <div style="font-size: 13px; color: #6b7280;">Taux d'occupation</div>
              <div style="font-size: 28px; font-weight: 800; color: #ef4444;">78%</div>
            </div>
          </div>
          <div style="font-size: 12px; color: #10b981;">+5% vs mois dernier</div>
        </div>
      </div>

      <!-- Graphiques principaux -->
      <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px; margin-bottom: 24px;">
        <!-- Évolution des ventes -->
        <div class="card">
          <div class="card__header">
            <h3>Évolution des ventes</h3>
          </div>
          <div style="padding: 24px;">
            <div style="background: #f9fafb; border-radius: 12px; padding: 40px; text-align: center; min-height: 300px; display: flex; align-items: center; justify-content: center;">
              <div>
                <i data-feather="bar-chart-2" style="width: 64px; height: 64px; color: #9ca3af; margin-bottom: 16px;"></i>
                <div style="color: #6b7280; font-size: 14px;">Graphique d'évolution des ventes par jour</div>
                <div style="color: #9ca3af; font-size: 12px; margin-top: 8px;">Intégration Chart.js à venir</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Top 5 trajets -->
        <div class="card">
          <div class="card__header">
            <h3>Top 5 trajets</h3>
          </div>
          <div style="padding: 20px;">
            <div style="display: flex; flex-direction: column; gap: 12px;">
              <div style="padding: 12px; background: #f9fafb; border-radius: 8px; border-left: 4px solid #1B4B7F;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                  <span style="font-weight: 600; font-size: 14px;">Kinshasa → Matadi</span>
                  <span style="font-weight: 700; color: #1B4B7F;">2,985</span>
                </div>
                <div style="font-size: 12px; color: #6b7280;">35% des ventes</div>
              </div>

              <div style="padding: 12px; background: #f9fafb; border-radius: 8px; border-left: 4px solid #10b981;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                  <span style="font-weight: 600; font-size: 14px;">Kinshasa → Lubumbashi</span>
                  <span style="font-weight: 700; color: #10b981;">2,137</span>
                </div>
                <div style="font-size: 12px; color: #6b7280;">25% des ventes</div>
              </div>

              <div style="padding: 12px; background: #f9fafb; border-radius: 8px; border-left: 4px solid #f59e0b;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                  <span style="font-weight: 600; font-size: 14px;">Kinshasa → Kikwit</span>
                  <span style="font-weight: 700; color: #f59e0b;">1,709</span>
                </div>
                <div style="font-size: 12px; color: #6b7280;">20% des ventes</div>
              </div>

              <div style="padding: 12px; background: #f9fafb; border-radius: 8px; border-left: 4px solid #3b82f6;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                  <span style="font-weight: 600; font-size: 14px;">Matadi → Kinshasa</span>
                  <span style="font-weight: 700; color: #3b82f6;">1,026</span>
                </div>
                <div style="font-size: 12px; color: #6b7280;">12% des ventes</div>
              </div>

              <div style="padding: 12px; background: #f9fafb; border-radius: 8px; border-left: 4px solid #ef4444;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                  <span style="font-weight: 600; font-size: 14px;">Kinshasa Gare → Lemba</span>
                  <span style="font-weight: 700; color: #ef4444;">690</span>
                </div>
                <div style="font-size: 12px; color: #6b7280;">8% des ventes</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Statistiques par canal et par type -->
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
        <!-- Ventes par canal -->
        <div class="card">
          <div class="card__header">
            <h3>Ventes par canal</h3>
          </div>
          <div style="padding: 24px;">
            <div style="display: flex; flex-direction: column; gap: 16px;">
              <div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                  <span style="font-weight: 600;">Vente en ligne</span>
                  <span style="font-weight: 700; color: #1B4B7F;">3,245 (38%)</span>
                </div>
                <div style="width: 100%; height: 8px; background: #e5e7eb; border-radius: 4px; overflow: hidden;">
                  <div style="width: 38%; height: 100%; background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);"></div>
                </div>
              </div>

              <div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                  <span style="font-weight: 600;">Application mobile</span>
                  <span style="font-weight: 700; color: #1B4B7F;">2,847 (33%)</span>
                </div>
                <div style="width: 100%; height: 8px; background: #e5e7eb; border-radius: 4px; overflow: hidden;">
                  <div style="width: 33%; height: 100%; background: linear-gradient(90deg, #f093fb 0%, #f5576c 100%);"></div>
                </div>
              </div>

              <div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                  <span style="font-weight: 600;">Guichets physiques</span>
                  <span style="font-weight: 700; color: #1B4B7F;">1,985 (23%)</span>
                </div>
                <div style="width: 100%; height: 8px; background: #e5e7eb; border-radius: 4px; overflow: hidden;">
                  <div style="width: 23%; height: 100%; background: linear-gradient(90deg, #4facfe 0%, #00f2fe 100%);"></div>
                </div>
              </div>

              <div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                  <span style="font-weight: 600;">Partenaires</span>
                  <span style="font-weight: 700; color: #1B4B7F;">470 (6%)</span>
                </div>
                <div style="width: 100%; height: 8px; background: #e5e7eb; border-radius: 4px; overflow: hidden;">
                  <div style="width: 6%; height: 100%; background: linear-gradient(90deg, #fa709a 0%, #fee140 100%);"></div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Ventes par type de client -->
        <div class="card">
          <div class="card__header">
            <h3>Ventes par type de client</h3>
          </div>
          <div style="padding: 24px;">
            <div style="display: flex; flex-direction: column; gap: 16px;">
              <div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                  <span style="font-weight: 600;">Standard</span>
                  <span style="font-weight: 700; color: #1B4B7F;">4,274 (50%)</span>
                </div>
                <div style="width: 100%; height: 8px; background: #e5e7eb; border-radius: 4px; overflow: hidden;">
                  <div style="width: 50%; height: 100%; background: #10b981;"></div>
                </div>
              </div>

              <div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                  <span style="font-weight: 600;">Étudiant (-15%)</span>
                  <span style="font-weight: 700; color: #1B4B7F;">2,564 (30%)</span>
                </div>
                <div style="width: 100%; height: 8px; background: #e5e7eb; border-radius: 4px; overflow: hidden;">
                  <div style="width: 30%; height: 100%; background: #3b82f6;"></div>
                </div>
              </div>

              <div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                  <span style="font-weight: 600;">Entreprise (-20%)</span>
                  <span style="font-weight: 700; color: #1B4B7F;">1,282 (15%)</span>
                </div>
                <div style="width: 100%; height: 8px; background: #e5e7eb; border-radius: 4px; overflow: hidden;">
                  <div style="width: 15%; height: 100%; background: #f59e0b;"></div>
                </div>
              </div>

              <div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                  <span style="font-weight: 600;">Senior (-10%)</span>
                  <span style="font-weight: 700; color: #1B4B7F;">342 (4%)</span>
                </div>
                <div style="width: 100%; height: 8px; background: #e5e7eb; border-radius: 4px; overflow: hidden;">
                  <div style="width: 4%; height: 100%; background: #ef4444;"></div>
                </div>
              </div>

              <div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                  <span style="font-weight: 600;">Enfant (-20%)</span>
                  <span style="font-weight: 700; color: #1B4B7F;">85 (1%)</span>
                </div>
                <div style="width: 100%; height: 8px; background: #e5e7eb; border-radius: 4px; overflow: hidden;">
                  <div style="width: 1%; height: 100%; background: #8b5cf6;"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Performance par période -->
      <div class="card" style="margin-bottom: 24px;">
        <div class="card__header">
          <h3>Performance par jour de la semaine</h3>
        </div>
        <div style="padding: 24px;">
          <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 12px;">
            <div style="text-align: center; padding: 16px; background: #f9fafb; border-radius: 8px;">
              <div style="font-size: 12px; color: #6b7280; margin-bottom: 8px;">Lundi</div>
              <div style="font-weight: 700; font-size: 24px; color: #1B4B7F;">1,245</div>
              <div style="font-size: 11px; color: #10b981; margin-top: 4px;">+8%</div>
            </div>
            <div style="text-align: center; padding: 16px; background: #f9fafb; border-radius: 8px;">
              <div style="font-size: 12px; color: #6b7280; margin-bottom: 8px;">Mardi</div>
              <div style="font-weight: 700; font-size: 24px; color: #1B4B7F;">1,189</div>
              <div style="font-size: 11px; color: #10b981; margin-top: 4px;">+5%</div>
            </div>
            <div style="text-align: center; padding: 16px; background: #f9fafb; border-radius: 8px;">
              <div style="font-size: 12px; color: #6b7280; margin-bottom: 8px;">Mercredi</div>
              <div style="font-weight: 700; font-size: 24px; color: #1B4B7F;">1,098</div>
              <div style="font-size: 11px; color: #ef4444; margin-top: 4px;">-3%</div>
            </div>
            <div style="text-align: center; padding: 16px; background: #f9fafb; border-radius: 8px;">
              <div style="font-size: 12px; color: #6b7280; margin-bottom: 8px;">Jeudi</div>
              <div style="font-weight: 700; font-size: 24px; color: #1B4B7F;">1,156</div>
              <div style="font-size: 11px; color: #10b981; margin-top: 4px;">+2%</div>
            </div>
            <div style="text-align: center; padding: 16px; background: #dbeafe; border-radius: 8px; border: 2px solid #1B4B7F;">
              <div style="font-size: 12px; color: #1B4B7F; margin-bottom: 8px; font-weight: 600;">Vendredi</div>
              <div style="font-weight: 800; font-size: 24px; color: #1B4B7F;">1,587</div>
              <div style="font-size: 11px; color: #10b981; margin-top: 4px;">+18%</div>
            </div>
            <div style="text-align: center; padding: 16px; background: #dcfce7; border-radius: 8px; border: 2px solid #10b981;">
              <div style="font-size: 12px; color: #10b981; margin-bottom: 8px; font-weight: 600;">Samedi</div>
              <div style="font-weight: 800; font-size: 24px; color: #10b981;">1,742</div>
              <div style="font-size: 11px; color: #10b981; margin-top: 4px;">+25%</div>
            </div>
            <div style="text-align: center; padding: 16px; background: #fef3c7; border-radius: 8px; border: 2px solid #f59e0b;">
              <div style="font-size: 12px; color: #f59e0b; margin-bottom: 8px; font-weight: 600;">Dimanche</div>
              <div style="font-weight: 800; font-size: 24px; color: #f59e0b;">1,530</div>
              <div style="font-size: 11px; color: #10b981; margin-top: 4px;">+15%</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Indicateurs supplémentaires -->
      <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; margin-bottom: 24px;">
        <!-- Réservations vs Achats directs -->
        <div class="card">
          <div class="card__header">
            <h3>Type d'achat</h3>
          </div>
          <div style="padding: 24px;">
            <div style="display: flex; flex-direction: column; gap: 16px;">
              <div style="text-align: center; padding: 20px; background: #dbeafe; border-radius: 8px;">
                <div style="font-size: 13px; color: #1e40af; margin-bottom: 8px;">Achats directs</div>
                <div style="font-weight: 800; font-size: 32px; color: #1B4B7F;">6,838</div>
                <div style="font-size: 12px; color: #1e40af; margin-top: 4px;">80% des ventes</div>
              </div>
              <div style="text-align: center; padding: 20px; background: #fef3c7; border-radius: 8px;">
                <div style="font-size: 13px; color: #92400e; margin-bottom: 8px;">Réservations</div>
                <div style="font-weight: 800; font-size: 32px; color: #f59e0b;">1,709</div>
                <div style="font-size: 12px; color: #92400e; margin-top: 4px;">20% des ventes</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Annulations -->
        <div class="card">
          <div class="card__header">
            <h3>Annulations</h3>
          </div>
          <div style="padding: 24px;">
            <div style="text-align: center; padding: 20px; background: #fee2e2; border-radius: 8px; margin-bottom: 16px;">
              <div style="font-size: 13px; color: #991b1b; margin-bottom: 8px;">Total annulé</div>
              <div style="font-weight: 800; font-size: 32px; color: #ef4444;">247</div>
              <div style="font-size: 12px; color: #991b1b; margin-top: 4px;">2.8% des ventes</div>
            </div>
            <div style="background: #f9fafb; padding: 12px; border-radius: 8px;">
              <div style="font-size: 12px; color: #6b7280; margin-bottom: 8px;">Raisons principales :</div>
              <div style="font-size: 13px; line-height: 1.8;">
                <div>• Changement de plan (45%)</div>
                <div>• Retard du bus (30%)</div>
                <div>• Erreur de réservation (25%)</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Cartes prépayées -->
        <div class="card">
          <div class="card__header">
            <h3>Cartes prépayées</h3>
          </div>
          <div style="padding: 24px;">
            <div style="text-align: center; padding: 20px; background: #dcfce7; border-radius: 8px; margin-bottom: 16px;">
              <div style="font-size: 13px; color: #065f46; margin-bottom: 8px;">Utilisation</div>
              <div style="font-weight: 800; font-size: 32px; color: #10b981;">1,425</div>
              <div style="font-size: 12px; color: #065f46; margin-top: 4px;">16.7% des ventes</div>
            </div>
            <div style="background: #f9fafb; padding: 12px; border-radius: 8px;">
              <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                <span style="font-size: 12px; color: #6b7280;">Cartes actives</span>
                <span style="font-weight: 600; font-size: 12px;">234</span>
              </div>
              <div style="display: flex; justify-content: space-between;">
                <span style="font-size: 12px; color: #6b7280;">Solde total</span>
                <span style="font-weight: 600; font-size: 12px;">4.2M CDF</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <?php require_once 'includes/footer.php';  ?>
    </main>
  </div>

  <!-- Application principale -->
  <script src="Public/js/app.js"></script>
  
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      feather.replace();

      // Sélecteur de période
      document.getElementById('periodSelector')?.addEventListener('change', (e) => {
        if(e.target.value === 'custom') {
          alert('Fonctionnalité de sélection de période personnalisée à venir');
        } else {
          console.log('Période sélectionnée:', e.target.value);
          // Ici, on rechargerait les données selon la période
        }
      });
    });
  </script>
</body>
</html>
