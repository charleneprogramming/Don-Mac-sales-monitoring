import { Swiper, SwiperSlide } from 'swiper/react';
import { EffectCoverflow, Pagination, Autoplay } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/effect-coverflow';
import 'swiper/css/pagination';
import { useState } from 'react';

const images = [
  { src: '/images/don-caramel.png', label: 'Caramel Macchiato' },
  { src: '/images/don-matcha.png', label: 'Don Matchatos' },
  { src: '/images/don-spanish.png', label: 'Brown Spanish Latte' },
  { src: '/images/don-darko.png', label: 'Don Darko' },
  { src: '/images/don-berry.png', label: 'Don Berry' },
  { src: '/images/don-oreo.png', label: 'Oreo Coffee' },
];

export default function ImageCarousel() {
  const [realIndex, setRealIndex] = useState(0);

  return (
    <div style={{ maxWidth: '70vw', margin: '0 auto,', overflow: 'visible',}}>
      <Swiper
        modules={[EffectCoverflow, Pagination, Autoplay]}
        effect="coverflow"
        grabCursor={true}
        centeredSlides={true}
        loop={true}
        slidesPerView={2}
        autoplay={{
          delay: 4000,
          disableOnInteraction: false,
        }}
        coverflowEffect={{
          rotate: 0,
          stretch: 0,
          depth: 100,
          modifier: 2.5,
          slideShadows: false,
        }}
        pagination={{ clickable: true }}
        style={{ paddingBottom: '25px' }}
        onSlideChange={(swiper) => setRealIndex(swiper.realIndex)}
      >
        {images.map((image, idx) => (
          <SwiperSlide key={idx}>
            <div style={{ position: 'relative', width: '100%', padding: '100px' }}>
              <img
                src={image.src}
                alt={`Slide ${idx + 1}`}
                style={{
                  width: '40vw',
                  height: 'auto',
                  borderRadius: '10px',
    
                }}
              />
              {realIndex === idx && (
                <div
                  style={{
                    position: 'absolute',
                    bottom: '10px',
                    left: '50%',
                    transform: 'translateX(-50%)',
                    color: '#9a4c2e',
                    borderRadius: '5px',
                    fontSize: '1.5rem',
                    fontWeight: 'bold',
                  }}
                >
                  {image.label}
                </div>
              )}
            </div>
          </SwiperSlide>
        ))}
      </Swiper>
    </div>
  );
}
