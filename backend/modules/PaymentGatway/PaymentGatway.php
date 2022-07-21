<?php
namespace PaymentGatway;

/**
 * Modulo responsavel por fazer a ponte entre camada de service e os respectivos gatways de pagamento
 * gatwayName é o nome do gatway de pagamento: Vindi, mercado pago etc..
 * Padronizar modulos de gatway de pagamento sendo o nome do modulo : NomeEntidade+NomeGatwayPagamento+Service tudo em PascalCase
 *  Ex: vindi: CustomerVindiService. mercado pago: CustomerMercadoPagoService.
 * Padronizar nome do pacote onde estão os modulos sendo, o nome do gatway de pagamento seguido da palavra gatway, NomeGatwayPagamento+Gatway tudo em PascalCase
 *  Ex vindi: VindiGatway. mercado pago: MercadoPagoGatway
 *
 */
class PaymentGatway {

    public static $gatwayName = "Vindi";

    public static function Customer($acountCenterEntity) {
        $gatwayModule = self::mountGatwayName("Customer");
        return new $gatwayModule($acountCenterEntity);
    }

    public static function Subscription($acountCenterEntity) {
        $gatwayModule = self::mountGatwayName("Subscription");
        return new $gatwayModule($acountCenterEntity);
    }

    private static function mountGatwayName($moduleName) {
        $fullModuleName = $moduleName.self::$gatwayName."Service";
        $gatwayModule = self::$gatwayName."Gatway\\".$fullModuleName;
        return $gatwayModule;
    }
}
