<template>
  <b-card>
    <b-row>
      <b-col lg="6" class="my-1">
        <b-form-group
          label="Ordenar"
          label-cols-sm="3"
          label-align-sm="right"
          label-size="sm"
          label-for="sortBySelect"
          class="mb-0"
        >
          <b-input-group size="sm">
            <b-form-select
              v-model="sortBy"
              id="sortBySelect"
              :options="sortOptions"
              class="w-75"
            >
              <template v-slot:first>
                <option value>-- ninguno --</option>
              </template>
            </b-form-select>
            <b-form-select
              v-model="sortDesc"
              size="sm"
              :disabled="!sortBy"
              class="w-25"
            >
              <option :value="false">Asc</option>
              <option :value="true">Desc</option>
            </b-form-select>
          </b-input-group>
        </b-form-group>
      </b-col>
      <b-col lg="6" class="my-1">
        <b-form-group
          label="Buscar"
          label-cols-sm="3"
          label-align-sm="right"
          label-size="sm"
          label-for="filterInput"
          class="mb-0"
        >
          <b-input-group size="sm">
            <b-form-input
              v-model="filter"
              type="search"
              id="filterInput"
              placeholder="Busca algo"
            ></b-form-input>
            <b-input-group-append>
              <b-button :disabled="!filter" @click="filter = ''"
                >Limpiar</b-button
              >
            </b-input-group-append>
            <b-button class="btn btn-info btn-sm" @click="exportarExcel">
              Exportar XLSX
            </b-button>
          </b-input-group>
        </b-form-group>
      </b-col>

      <b-col sm="5" md="6" class="my-1">
        <b-form-group
          label="Por pagina"
          label-cols-sm="6"
          label-cols-md="4"
          label-cols-lg="3"
          label-align-sm="right"
          label-size="sm"
          label-for="perPageSelect"
          class="mb-0"
        >
          <b-form-select
            v-model="perPage"
            id="perPageSelect"
            size="sm"
            :options="pageOptions"
          ></b-form-select>
        </b-form-group>
      </b-col>

      <b-col sm="7" md="6" class="my-1">
        <b-pagination
          v-model="currentPage"
          :total-rows="totalRows"
          :per-page="perPage"
          align="fill"
          size="sm"
          class="my-0"
        ></b-pagination>
      </b-col>
    </b-row>

    <!-- Tabla principal -->
    <div class="table-responsive">
      <b-table
        show-empty
        small
        stacked="md"
        :busy="cargando"
        :items="arrayPedidos"
        :fields="fields"
        :current-page="currentPage"
        :per-page="perPage"
        :filter="filter"
        :filterIncludedFields="filterOn"
        :sort-by.sync="sortBy"
        :sort-desc.sync="sortDesc"
        :sort-direction="sortDirection"
        :tbody-tr-class="rowClass"
        @filtered="onFiltered"
        :emptyText="'No hay elementos para mostrar'"
        :emptyFilteredText="'No se han encontrado elementos para lo que buscas'"
      >
        <template v-slot:cell(index)="row">{{ row.index + 1 }}</template>
        <template #cell(voucher)="data">
          <div
            class="d-flex flex-wrap justify-content-between align-items-center"
            style="width: 100px"
          >
            <div v-for="imagen in data.item.imagenes" :key="imagen">
              <b-tooltip
                :target="`imagen-${imagen}`"
                triggers="hover"
                variant="light"
                v-if="imagen != 'default.jpg'"
              >
                <b-button
                  size="sm"
                  @click="
                    showImageClick(`/uploads/vouchers_por_pedido/${imagen}`)
                  "
                  variant="info"
                >
                  <i class="icon-eye"></i>
                </b-button>
                <b-button
                  size="sm"
                  @click="eliminarFoto(imagen)"
                  variant="danger"
                >
                  <i class="icon-trash"></i>
                </b-button>
              </b-tooltip>
              <template v-if="data.item.imagenes.length > 1">
                <b-img
                  :id="`imagen-${imagen}`"
                  center
                  :src="`/uploads/vouchers_por_pedido/${imagen}`"
                  :class="imagen != 'default.jpg' ? 'icono-upload' : ''"
                  style="width: 30px"
                  v-if="imagen != 'default.jpg'"
                ></b-img>
              </template>
              <template v-if="data.item.imagenes.length <= 1">
                <b-img
                  :id="`imagen-${imagen}`"
                  center
                  :src="`/uploads/vouchers_por_pedido/default.jpg`"
                  :class="imagen != 'default.jpg' ? 'icono-upload' : ''"
                  style="width: 30px"
                ></b-img>
              </template>
            </div>
          </div>
        </template>
        <template #cell(fecha)="data">
          {{ data.item.fecha }} - {{ data.item.hora }}
        </template>
        <template #cell(por_cobrar)="data">
          {{
            (
              Number(data.item.precio_productos) +
              Number(data.item.envio_productos) -
              Number(data.item.monto_pagado)
            ).toFixed(2)
          }}
        </template>

        <template v-slot:cell(actions)="row">
          <div>
            <b-button
              variant="success"
              size="sm"
              @click="abrirModalSubirImagen(row.item)"
            >
              <b-icon icon="upload"></b-icon>
            </b-button>
            <b-button
              size="sm"
              variant="info"
              @click="abrirModalVerPedido(row.item)"
              v-if="usuarioLogeado.idrole != 4"
            >
              <i class="icon-eye"></i>
            </b-button>
            <b-button
              size="sm"
              variant="warning"
              @click="abrirModalEditarPedido(row.item)"
            >
              <i class="icon-pencil"></i>
            </b-button>
            <b-button
              size="sm"
              variant="danger"
              @click="anularPedido(row.item, row.index)"
              v-if="usuarioLogeado.idrole != 4"
            >
              <i class="icon-trash"></i>
            </b-button>
            <!--<b-button
              variant="danger"
              size="sm"
              @click="eliminarItem(row.item, row.index)"
            >
              <i class="icon-trash"></i>
            </b-button>-->
          </div>
        </template>
        <template v-slot:table-busy>
          <div class="text-center text-danger my-2">
            <b-spinner class="align-middle"></b-spinner>
            <strong>Cargando...</strong>
          </div>
        </template>
      </b-table>
    </div>
    <div>
      <i class="fa fa-square-o mr-2" style="width: 15px; height: 15px"></i
      ><span>Pendiente</span>
      <i
        class="fa fa-square-o text-danger bg-danger mr-2"
        style="width: 15px; height: 15px; border-radius: 5px"
      ></i
      ><span class="mr-2">Cancelado</span>
      <i
        class="fa fa-square-o text-warning bg-warning mr-2"
        style="width: 15px; height: 15px; border-radius: 5px"
      ></i
      ><span class="mr-2">Recogido</span>
      <i
        class="fa fa-square-o text-info bg-info mr-2"
        style="width: 15px; height: 15px; border-radius: 5px"
      ></i
      ><span class="mr-2">Pagado</span>
      <i
        class="fa fa-square-o text-dark bg-dark mr-2"
        style="width: 15px; height: 15px; border-radius: 5px"
      ></i
      ><span class="mr-2">Entregado</span>
      <i
        class="fa fa-square-o text-success bg-success mr-2"
        style="width: 15px; height: 15px; border-radius: 5px"
      ></i
      ><span class="mr-2">Completado</span>
    </div>

    <div
      class="modal fade"
      tabindex="-1"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
      id="modalSubirImagen"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Subir imagen</h5>
            <button
              type="button"
              class="close"
              @click="cerrarModal('#modalSubirImagen')"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <vue-dropzone
              ref="myVueDropzone"
              id="dropzone"
              :useCustomSlot="true"
              :options="dropzoneOptions"
              v-on:vdropzone-sending="sendingEvent"
            >
              <div class="dropzone-custom-content">
                <h3 class="dropzone-custom-title">
                  Arrastra y suelta aquí el voucher!
                </h3>
                <div class="subtitle">
                  ...o has click aquí para seleccionar la foto desde tu
                  computadora
                </div>
              </div>
            </vue-dropzone>
          </div>
        </div>
      </div>
    </div>
    <div
      class="modal fade bd-example-modal-lg1"
      id="modalEnvioEditar"
      tabindex="-1"
      role="dialog"
      aria-labelledby="myLargeModalLabel"
      aria-hidden="true"
      ref="modalEnvioEditar"
      style="overflow-y: scroll"
    >
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Editar pedido</h5>
            <button
              type="button"
              class="close"
              @click="cerrarModal('#modalEnvioEditar')"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="card-body">
            <form action="" @submit.prevent="editarPedido()">
              <div class="form-row">
                <template v-if="usuarioLogeado.idrole != 4">
                  <div class="form-group col-md-3">
                    <label>Responsable</label>
                    <select
                      required="required"
                      class="form-control"
                      v-model="objetoEnvio.id_usuario"
                    >
                      <option
                        v-for="usuario in arrayUsuarios"
                        :value="usuario.id"
                        :key="usuario.key"
                      >
                        {{ usuario.usuario }}
                      </option>
                    </select>
                  </div>
                  <div class="form-group col-md-3">
                    <label>Fecha</label>
                    <input
                      type="date"
                      name="FechaReg"
                      id="FechaReg_edit"
                      class="form-control"
                      v-model="objetoEnvio.fecha"
                    />
                  </div>
                  <div class="form-group col-md-3">
                    <label>Cliente</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="objetoEnvio.cliente"
                    />
                  </div>
                  <div class="form-group col-md-3">
                    <label>Teléfono</label>
                    <input
                      type="number"
                      class="form-control"
                      v-model="objetoEnvio.telefono"
                    />
                  </div>
                  <div class="form-group col-md-3">
                    <label>Dirección</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="objetoEnvio.direccion"
                    />
                  </div>
                  <div class="form-group col-md-3">
                    <label>Referencia</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="objetoEnvio.referencia"
                    />
                  </div>
                  <div class="form-group col-md-3">
                    <label>Distrito</label>
                    <select v-model="distritoSeleccionado" class="form-control">
                      <option disabled value="">Escoje un distrito</option>
                      <option
                        v-for="distrito in arrayDistritosDelivery"
                        :key="distrito.id"
                        :value="distrito"
                      >
                        {{ distrito.distrito }}
                      </option>
                    </select>
                  </div>
                  <div class="form-group col-md-3">
                    <label>Medio de recepción</label>
                    <select
                      v-model="objetoEnvio.medio_recepcion"
                      class="form-control"
                    >
                      <option disabled value="">Escoje un medio</option>
                      <option value="facebook">Facebook</option>
                      <option value="whatsapp">WhatsApp</option>
                      <option value="instagram">Instagram</option>
                      <option value="web">Web</option>
                      <option value="mercado_libre">Mercado Libre</option>
                    </select>
                  </div>
                  <div class="form-group col-md-3">
                    <label>Método de pago</label>
                    <select
                      v-model="objetoEnvio.tipo_pago"
                      class="form-control"
                    >
                      <option disabled value="">
                        Escoje un método de pago
                      </option>
                      <option
                        v-for="tipo_pago in arrayTipoDePagos"
                        :key="tipo_pago.id"
                        :value="tipo_pago.id"
                      >
                        {{ tipo_pago.nombre }}
                      </option>
                    </select>
                  </div>
                  <div class="form-group col-md-3">
                    <label>Observación SCP</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="objetoEnvio.observacion_empresa"
                    />
                  </div>
                  <div class="form-group col-md-3">
                    <label>Observación Listos YA!</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="objetoEnvio.observacion_delivery"
                      readonly
                    />
                  </div>
                  <div class="form-group col-md-3">
                    <label>Precio de los productos</label>
                    <input
                      type="number"
                      step="any"
                      class="form-control"
                      id="precio_productos_edit"
                      v-model="objetoEnvio.precio_productos"
                    />
                  </div>
                  <div class="form-group col-md-3">
                    <label>Costo de envío</label>
                    <input
                      type="number"
                      step="any"
                      class="form-control"
                      id="costo_envio_edit"
                      v-model="objetoEnvio.envio_productos"
                    />
                  </div>
                  <div class="form-group col-md-3">
                    <label>Monto pagado</label>
                    <input
                      type="number"
                      step="any"
                      class="form-control"
                      id="monto_pagado_edit"
                      v-model="objetoEnvio.monto_pagado"
                    />
                  </div>
                  <div class="form-group col-md-3">
                    <label>Por cobrar</label>
                    <input
                      type="number"
                      step="any"
                      class="form-control"
                      id="por_cobrar_edit"
                      v-model="objetoEnvio.por_cobrar"
                      disabled
                    />
                  </div>
                  <div class="form-group col-md-3">
                    <label>Estado</label>
                    <select v-model="objetoEnvio.estado" class="form-control">
                      <option disabled value="">Selecciona un estado</option>
                      <option value="pendiente">Pendiente</option>
                      <option value="cancelado">Cancelado</option>
                      <option value="pagado">Pagado</option>
                      <option value="recogido">Recogido</option>
                      <option value="entregado">Entregado</option>
                      <option value="completado">Completado</option>
                    </select>
                  </div>
                </template>
                <template v-else>
                  <div class="form-group col-md-3">
                    <label>Observación Listos YA!</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="objetoEnvio.observacion_delivery"
                    />
                  </div>
                  <div class="form-group col-md-3">
                    <label>Observación SCP</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="objetoEnvio.observacion_empresa"
                      readonly
                    />
                  </div>
                  <div class="form-group col-md-3">
                    <label>Estado</label>
                    <select v-model="objetoEnvio.estado" class="form-control">
                      <option disabled value="">Selecciona un estado</option>
                      <option value="pendiente">Pendiente</option>
                      <option value="cancelado">Cancelado</option>
                      <option value="pagado">Pagado</option>
                      <option value="recogido">Recogido</option>
                      <option value="entregado">Entregado</option>
                      <option value="completado">Completado</option>
                    </select>
                  </div>
                  <div class="form-group col-md-3">
                    <label>Método de pago</label>
                    <select
                      v-model="objetoEnvio.tipo_pago"
                      class="form-control"
                    >
                      <option disabled value="">
                        Escoje un método de pago
                      </option>
                      <option
                        v-for="tipo_pago in arrayTipoDePagos"
                        :key="tipo_pago.id"
                        :value="tipo_pago.id"
                      >
                        {{ tipo_pago.nombre }}
                      </option>
                    </select>
                  </div>
                </template>
              </div>
              <hr />
              <div class="form-group" v-if="usuarioLogeado.idrole != 4">
                <label>Pedido</label>
                <textarea
                  class="form-control"
                  rows="3"
                  v-model="objetoEnvio.pedido"
                ></textarea>
              </div>
              <div class="modal-footer">
                <button
                  type="button"
                  class="btn btn-secondary"
                  @click="cerrarModal('#modalEnvioEditar')"
                >
                  Cerrar
                </button>
                <button type="submit" class="btn btn-primary">Guardar</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div
      class="modal fade bd-example-modal-lg"
      tabindex="-1"
      role="dialog"
      id="modalDetallePedido"
      aria-labelledby="myLargeModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">
              Detalles del pedido
            </h5>
            <button
              type="button"
              class="close"
              @click="cerrarModalVerPedido()"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <DetallesPedido :pedido="pedidoSeleccionado"></DetallesPedido>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              @click="cerrarModalVerPedido()"
            >
              Cerrar
            </button>
          </div>
        </div>
      </div>
    </div>

    <viewer v-model="showImage" :src="imageSrc" v-if="!modalEnvioControl">
    </viewer>
    <!-- User Interface controls -->
  </b-card>
</template>

<script>
import DetallesPedido from "../components/comunes/DetallesPedido";
import Loading from "vue-loading-overlay";
// Import stylesheet
import "vue-loading-overlay/dist/vue-loading.css";
import XLSX from "xlsx";
import Viewer from "vue-viewer";
import vue2Dropzone from "vue2-dropzone";
import "vue2-dropzone/dist/vue2Dropzone.min.css";
export default {
  props: {
    dia: Object,
  },
  mounted() {
    $(this.$refs.modalEnvioEditar).on(
      "hidden.bs.modal",
      this.limpiarModalEditarEnvio
    );
    this.seleccionarDistrito();
  },
  data() {
    return {
      totalRows: 1,
      currentPage: 1,
      perPage: 10,
      pageOptions: [5, 10, 15],
      sortBy: "",
      sortDesc: false,
      sortDirection: "asc",
      filter: null,
      filterOn: [],
      fields2: [
        {
          key: "index",
          label: "#",
          sortable: true,
          sortDirection: "desc",
          class: "text-center",
          visible: true,
        },
        {
          key: "cliente",
          label: "Cliente",
          sortable: true,
          class: "text-center",
          visible: true,
        },
        {
          key: "responsable",
          label: "Responsable",
          sortable: true,
          class: "text-center",
          visible:
            this.$store.getters.arrayUsuarioLogeado.idrole != 4 ? true : false,
        },
        {
          key: "fecha",
          label: "Fecha de entrega",
          sortable: true,
          class: "text-center",
          visible: true,
        },
        {
          key: "created_at",
          label: "Fecha de registro",
          sortable: true,
          class: "text-center",
          visible: true
        },
        {
          key: "telefono",
          label: "Teléfono",
          sortable: true,
          class: "text-center",
          visible:
            this.$store.getters.arrayUsuarioLogeado.idrole == 4 ? true : false,
        },
        {
          key: "distrito",
          label: "Distrito",
          sortable: true,
          class: "text-center",
          visible: true,
        },
        {
          key: "precio_productos",
          label: "Precio productos",
          sortable: true,
          class: "text-center",
          visible:
            this.$store.getters.arrayUsuarioLogeado.idrole != 4 ? true : false,
        },
        {
          key: "envio_productos",
          label: "Envio",
          sortable: true,
          class: "text-center",
          visible: true,
        },
        {
          key: "monto_pagado",
          label: "Monto pagado",
          sortable: true,
          class: "text-center",
          visible:
            this.$store.getters.arrayUsuarioLogeado.idrole != 4 ? true : false,
        },
        {
          key: "por_cobrar",
          label: "Total a cobrar",
          sortable: true,
          class: "text-center",
          visible: true,
        },
        {
          key: "observacion_empresa",
          label: "Observacion SCP",
          sortable: true,
          class: "text-center",
          visible:
            this.$store.getters.arrayUsuarioLogeado.idrole != 4 ? false : true,
        },
        {
          key: "voucher",
          label: "Voucher",
          class: "text-center",
          visible: true,
        },
        {
          key: "actions",
          label: "Acciones",
          class: "text-center",
          visible: true,
        },
      ],
      arrayPedidos: [],
      cargando: false,
      comprobanteSeleccionado: null,
      dropzoneOptions: {
        url: "/delivery/subirVoucherPorPedido",
        headers: {
          "X-CSRF-TOKEN": document.head.querySelector("[name=csrf-token]")
            .content,
        },
      },
      pedidoSeleccionado: null,
      showImage: false,
      imageSrc: "",
      cargandoInsertarImagen: false,
      objetoEnvio: {
        id_usuario: null,
        fecha: null,
        cliente: null,
        telefono: null,
        direccion: null,
        referencia: null,
        distrito: null,
        pedido: null,
        observacion_empresa: null,
        observacion_delivery: null,
        tipo_pago: "efectivo",
        medio_recepcion: "facebook",
        precio_productos: 0.0,
        envio_productos: 0.0,
        total_pedido: 0.0,
        monto_pagado: 0.0,
        por_cobrar: 0.0,
        voucher: null,
        estado: "pendiente",
      },
      distritoSeleccionado: {},
      arrayDistritosDelivery: [],
      pedidoSeleccionado: {},
    };
  },
  components: {
    Viewer,
    Loading,
    vueDropzone: vue2Dropzone,
    DetallesPedido,
  },
  computed: {
    sortOptions() {
      // Create an options list from our fields
      return this.fields
        .filter((f) => f.sortable)
        .map((f) => {
          return { text: f.label, value: f.key };
        });
    },
    usuarioLogeado() {
      return this.$store.getters.arrayUsuarioLogeado;
    },
    arrayUsuarios() {
      return this.$store.getters.arrayUsuarios;
    },
    modalEnvioControl() {
      return this.$store.getters.modalEnvioControl;
    },
    fields() {
      return this.fields2.filter((field) => field.visible);
    },
    arrayTipoDePagos() {
      return this.$store.getters.arrayTipoDePagos;
    },
  },
  methods: {
    rowClass(item, type) {
      if (!item || type !== "row") return;
      if (item.estado == "cancelado") return "bg-danger text-dark";
      if (item.estado == "completado") return "bg-success text-dark";
      if (item.estado == "recogido") return "bg-warning text-dark";
      if (item.estado == "entregado") return "bg-dark text-light";
      if (item.estado == "pagado") return "bg-info text-dark";
    },
    showImageClick(imagen) {
      this.imageSrc = imagen;
      this.showImage = true;
    },
    onFiltered(filteredItems) {
      // Trigger pagination to update the number of buttons/pages due to filtering
      this.totalRows = filteredItems.length;
      this.currentPage = 1;
    },
    seleccionarDistrito() {
      let me = this;
      var url = "/precio_delivery";
      axios.get(url).then(function (response) {
        me.arrayDistritosDelivery = response.data;
      });
    },
    isMobile() {
      if (
        /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(
          navigator.userAgent
        )
      ) {
        return true;
      } else {
        return false;
      }
    },
    abrirModalSubirImagen(pedido) {
      this.pedidoSeleccionado = pedido;
      $("#modalSubirImagen").modal("show");
    },
    sendingEvent(file, xhr, formData) {
      formData.append("id_pedido", this.pedidoSeleccionado.id);
      this.obtenerPedidosPorDia();
    },
    anularPedido(item, index) {
      Vue.swal({
        title: "Advertencia",
        text:
          "¿Estás seguro de anular este pedido? !Esta acción no se puede deshacer!",
        icon: "warning",
      }).then((result) => {
        if (result.value) {
          this.$store.dispatch("actualizarShow", true);
          axios
            .put(`delivery/${item.id}/anularPedido`)
            .then((response) => {
              this.$store.dispatch("actualizarShow", false);
              this.arrayPedidos.splice(index, 1);
              this.listarEnvios();
            })
            .catch((err) => {
              console.log(err);
            });
        }
      });
    },
    obtenerPedidoSeleccionado(pedido) {
      this.pedidoSeleccionado = pedido;
    },
    cerrarModal(modal) {
      $(modal).modal("hide");
    },
    async listarEnvios() {
      await this.$store.dispatch("cargarDiasDePedidos");
    },
    obtenerPedidosPorDia() {
      this.cargando = true;
      axios
        .get(`/delivery/${this.dia.fecha}/obtenerPedidosPorFecha`)
        .then((response) => {
          this.arrayPedidos = response.data;
          this.cargando = false;
        });
    },
    exportarExcel() {
      this.cargando = true;
      axios
        .get(
          `/delivery/${this.dia.fecha}/obtenerPedidosPorFechaParaExportarExcel`
        )
        .then((response) => {


          var respuesta = response.data;

          
          let objectMaxLength = [];
          for (let i = 0; i < respuesta.length; i++) {
            if(respuesta[i].tipo_pago == 'transferencia' || respuesta[i].tipo_pago == '-'){
              respuesta[i].tipo_pago = '-'
              respuesta[i].por_cobrar = '-'
            }
            let value = Object.values(respuesta[i]);
            for (let j = 0; j < value.length; j++) {
              if (typeof value[j] == "number" || !value[j]) {
                objectMaxLength[j] = 10;
              } else {
                objectMaxLength[j] =
                  objectMaxLength[j] >= value[j].length
                    ? objectMaxLength[j]
                    : value[j].length;
              }
            }
          }
          var wscols = [
            { width: objectMaxLength[0]+5 }, // first column
            { width: objectMaxLength[1]+5 }, // second column
            { width: objectMaxLength[2]+5 }, //...
            { width: objectMaxLength[3]+5 },
            { width: objectMaxLength[4]+5 },
            { width: objectMaxLength[5]+5 },
            { width: objectMaxLength[6]+5 },
            { width: objectMaxLength[7]+5 },
            { width: objectMaxLength[8]+5 },
            { width: objectMaxLength[9]+5 },
          ];

          let data = XLSX.utils.json_to_sheet(respuesta);
          data["!cols"] = wscols;
          data.A1.v = "Fecha";
          data.B1.v = "Cliente";
          data.C1.v = "Telefono";
          data.D1.v = "Direccion";
          data.E1.v = "Referencia";
          data.F1.v = "Distrito";
          data.G1.v = "Observacion";
          data.H1.v = "Metodo de pago";
          data.I1.v = "Por cobrar";
          const workbook = XLSX.utils.book_new();
          const filename = respuesta[0].fecha;
          XLSX.utils.book_append_sheet(workbook, data, filename);
          XLSX.writeFile(workbook, `${filename} - SpeedCuber Peru envios.xlsx`);
          this.cargando = false;
        });
    },
    eliminarFoto(nombreFotoVoucher) {
      if (confirm(`Deseas eliminar la foto ${nombreFotoVoucher}?`)) {
        axios
          .delete(`/voucher_pedido/${nombreFotoVoucher}`)
          .then((response) => {
            this.obtenerPedidosPorDia();
          });
      }
    },
    limpiarModalEditarEnvio() {
      this.$store.dispatch("actualizarModalEnvioControl", false);
    },
    abrirModalEditarPedido(pedido) {
      this.pedidoSeleccionado = pedido;
      axios.get(`delivery/${pedido.id}`).then((response) => {
        for (var i = 0; i < this.arrayDistritosDelivery.length; i++) {
          if (this.arrayDistritosDelivery[i].id == response.data.distrito) {
            this.distritoSeleccionado = this.arrayDistritosDelivery[i];
          }
        }
        this.objetoEnvio.id_usuario = response.data.id_usuario;
        this.objetoEnvio.fecha = response.data.fecha;
        this.objetoEnvio.cliente = response.data.cliente;
        this.objetoEnvio.telefono = response.data.telefono;
        this.objetoEnvio.direccion = response.data.direccion;
        this.objetoEnvio.referencia = response.data.referencia;
        this.objetoEnvio.pedido = response.data.pedido;
        this.objetoEnvio.observacion_empresa =
          response.data.observacion_empresa;
        this.objetoEnvio.observacion_delivery =
          response.data.observacion_delivery;
        this.objetoEnvio.tipo_pago = response.data.tipo_pago;
        this.objetoEnvio.medio_recepcion = response.data.medio_recepcion;
        this.objetoEnvio.precio_productos = response.data.precio_productos;
        this.objetoEnvio.envio_productos = response.data.envio_productos;
        this.objetoEnvio.total_pedido = response.data.total_pedido;
        this.objetoEnvio.monto_pagado = response.data.monto_pagado;
        this.objetoEnvio.por_cobrar = response.data.por_cobrar;
        this.objetoEnvio.estado = response.data.estado;

        this.$store.dispatch("actualizarModalEnvioControl", true);
        $("#modalEnvioEditar").modal("show");
      });
      //}
    },
    abrirModalVerPedido(pedido) {
      this.pedidoSeleccionado = pedido;
      $("#modalDetallePedido").modal("show");
    },
    cerrarModalVerPedido() {
      $("#modalDetallePedido").modal("hide");
    },
    editarPedido() {
      this.$store.dispatch("actualizarShow", true);
      axios
        .put(`delivery/${this.pedidoSeleccionado.id}`, this.objetoEnvio)
        .then((response) => {
          this.$store.dispatch("actualizarShow", false);
          $("#modalEnvioEditar").modal("hide");
          Vue.swal({
            title: `Pedido editado correctamente!`,
            text: `El pedido ha sido editado con éxito`,
            icon: "success",
          });
          this.obtenerPedidosPorDia();
          this.listarEnvios();
        })
        .catch((error) => {
          this.$store.dispatch("actualizarShow", false);
          Vue.swal({
            title: `Editar Pedido fallido`,
            text: `El pedido no pudo editarse`,
            icon: "error",
          });
        });
    },
  },
  watch: {
    dia() {
      this.obtenerPedidosPorDia();
    },
    distritoSeleccionado() {
      this.objetoEnvio.distrito = this.distritoSeleccionado.id;
      /*if (this.distritoSeleccionado.costo_envio) {
        this.objetoEnvio.envio_productos = this.distritoSeleccionado.costo_envio;
      }*/
    },
    "objetoEnvio.envio_productos"() {
      this.objetoEnvio.por_cobrar = (
        Number(this.objetoEnvio.precio_productos) +
        Number(this.objetoEnvio.envio_productos) -
        Number(this.objetoEnvio.monto_pagado)
      ).toFixed(2);
    },
    "objetoEnvio.precio_productos"() {
      this.objetoEnvio.por_cobrar = (
        Number(this.objetoEnvio.precio_productos) +
        Number(this.objetoEnvio.envio_productos) -
        Number(this.objetoEnvio.monto_pagado)
      ).toFixed(2);
    },
    "objetoEnvio.monto_pagado"() {
      this.objetoEnvio.por_cobrar = (
        Number(this.objetoEnvio.precio_productos) +
        Number(this.objetoEnvio.envio_productos) -
        Number(this.objetoEnvio.monto_pagado)
      ).toFixed(2);
    },
  },
};
</script>

<style scoped>
.avatar-edit {
  display: none;
}
.icono-upload {
  cursor: pointer;
}
.dropzone-custom-content {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  text-align: center;
}

.dropzone-custom-title {
  margin-top: 0;
  color: #00b782;
}

.subtitle {
  color: #314b5f;
}
</style>