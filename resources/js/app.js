import Swiper from "swiper";
import { Autoplay, Pagination, Navigation } from "swiper/modules";

import "swiper/css";
import "swiper/css/pagination";
import "swiper/css/navigation";

window.Swiper = Swiper;
window.SwiperModules = {
    Autoplay,
    Pagination,
    Navigation,
};
