import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('DOMContentLoaded', function() {
  const scoreElements = document.querySelectorAll('.score-circle');
  
  function updateScore(element) {
    try {
      const score = parseFloat(element.getAttribute('data-score')) || 0;
      const total = parseFloat(element.getAttribute('data-total')) || 1;
      const percentage = Math.min(100, Math.max(0, (score / total) * 100));
      
      // INVERTEMOS A PORCENTAGEM (100 - percentage)
      const invertedPercentage = 100 - percentage;
      element.style.setProperty('--percentage', `${invertedPercentage}%`);
      
      const scoreDisplay = element.querySelector('.score');
      const totalDisplay = element.querySelector('.total');
      
      scoreDisplay.textContent = Math.floor(score);
      totalDisplay.textContent = `/${Math.floor(total)}`;
      
      // Cores baseadas na porcentagem INVERTIDA
      if (invertedPercentage > 70) {
        scoreDisplay.style.color = '#00aa00'; // Verde forte
      } else if (invertedPercentage < 30) {
        scoreDisplay.style.color = '#ff0000'; // Vermelho
      } else {
        scoreDisplay.style.color = '#ff9900'; // Laranja
      }
      
    } catch (error) {
      console.error('Erro ao atualizar score:', error);
      element.querySelector('.score').textContent = '0';
      element.querySelector('.total').textContent = '/0';
    }
  }
  
  scoreElements.forEach(updateScore);

  // Observador para mudanças dinâmicas (opcional)
  const observer = new MutationObserver(mutations => {
    mutations.forEach(mutation => {
      if (mutation.type === 'attributes' && 
          (mutation.attributeName === 'data-score' || 
           mutation.attributeName === 'data-total')) {
        updateScore(mutation.target);
      }
    });
  });
  
  scoreElements.forEach(element => {
    observer.observe(element, { 
      attributes: true, 
      attributeFilter: ['data-score', 'data-total'] 
    });
  });
});