function linkopener(a){
    window.open(a,'_self');
}

const element1 = document.querySelector('.decouverte');
const encart1 = document.querySelector('.encart1');

const element2 = document.querySelector('.classique');
const encart2 = document.querySelector('.encart2');

const element3 = document.querySelector('.vip');
const encart3 = document.querySelector('.encart3');

element1.addEventListener('mouseover', () => {
  encart1.style.display = 'block';
});

element1.addEventListener('mouseout', () => {
  encart1.style.display = 'none';
});

element2.addEventListener('mouseover', () => {
    encart2.style.display = 'block';
  });
  
  element2.addEventListener('mouseout', () => {
    encart2.style.display = 'none';
  });


element3.addEventListener('mouseover', () => {
    encart3.style.display = 'block';
  });
  
  element3.addEventListener('mouseout', () => {
    encart3.style.display = 'none';
  });
  
